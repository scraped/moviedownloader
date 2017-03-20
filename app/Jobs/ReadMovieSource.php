<?php

namespace App\Jobs;

use App\Events\MovieRetrieved;
use App\Movie;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Symfony\Component\DomCrawler\Crawler;

class ReadMovieSource implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var string
     */
    protected $url;

    /**
     * @var Client
     */
    protected $httpClient;

    /**
     * @var Crawler
     */
    protected $domCrawler;

    /**
     * Create a new job instance.
     *
     * @param  string $url
     *
     * @return ReadMovieSource
     */
    public function __construct($url)
    {
        $this->url = $url;
    }

    /**
     * Execute the job.
     *
     * @param  Client $httpClient
     * @param  Crawler $domCrawler
     *
     * @throws \Exception
     *
     * @return void
     */
    public function handle(Client $httpClient, Crawler $domCrawler)
    {
        $this->httpClient = $httpClient;
        $this->domCrawler = $domCrawler;
        $this->domCrawler->addContent($this->getResponse());
        $movies = [];
        $this->domCrawler->filter('.poster .image')->each(function (Crawler $node) use (&$movies) {
            $movieName = $node->attr('alt');
            $movieUrl = $node->parents()->attr('data-film-slug');
            $movieUrl = "https://letterboxd.com{$movieUrl}";
            $this->domCrawler->clear();
            $this->domCrawler->addContent($this->getResponse($movieUrl));
            $movieYear = $this->domCrawler->filter('#poster-col .film-poster')->attr('data-film-release-year');
            $movies[] = [
                'name' => $movieName,
                'year' => $movieYear,
            ];
        });
        foreach ($movies as $movieData) {
            $movieExists = Movie::where('name', $movieData['name'])
                ->where('year', $movieData['year'])
                ->exists();
            if ($movieExists) {
                continue;
            }
            logger("Found new: {$movieData['name']} {$movieData['year']}");
            $movie = new Movie();
            $movie->name = $movieData['name'];
            $movie->year = $movieData['year'];
            $movie->status = 'searching';
            $movie->save();
            event(new MovieRetrieved($movie));
        }
    }

    /**
     * Get response for url
     *
     * @param  string $url
     *
     * @throws \Exception
     *
     * @return string
     */
    protected function getResponse($url = '')
    {
        $url = (empty($url)) ? $this->url : $url;
        $response = $this->httpClient->get($url);
        $responseStatus = $response->getStatusCode();
        if ($responseStatus != 200) {
            throw new \Exception("The url {$this->url} return the following status: {$responseStatus}");
        }

        return $response->getBody()->getContents();
    }
}
