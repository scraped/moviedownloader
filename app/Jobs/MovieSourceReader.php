<?php

namespace App\Jobs;

use App\Events\MovieSourceRead;
use GuzzleHttp\Client;
use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Symfony\Component\DomCrawler\Crawler;

class MovieSourceReader implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var string
     */
    protected $url;

    /**
     * @var string
     */
    protected $baseUrl;

    /**
     * @var Client
     */
    protected $httpClient;

    /**
     * @var Crawler
     */
    protected $domCrawler;

    /**
     * @var array
     */
    protected $movies;

    /**
     * Create a new job instance.
     *
     * @param  string $url
     *
     * @return MovieSourceReader
     */
    public function __construct($url)
    {
        $this->url = $url;
        // TODO: calculate
        $this->baseUrl = 'https://letterboxd.com';
        $this->movies = [];
    }

    /**
     * Execute the job.
     *
     * @param  Client $httpClient
     * @param  Crawler $domCrawler
     *
     * @return void
     */
    public function handle(Client $httpClient, Crawler $domCrawler)
    {
        $this->httpClient = $httpClient;
        $this->domCrawler = $domCrawler;
        $this->retrieveAndSetNameAndLink();
        $this->retrieveAndSetOtherData();
        event(new MovieSourceRead($this->movies));
    }

    /**
     * First request to get all movie names and links to other data
     *
     * @throws \Exception
     *
     * @return void
     */
    protected function retrieveAndSetNameAndLink()
    {
        $response = $this->httpClient->get($this->url);
        $responseStatus = $response->getStatusCode();
        if ($responseStatus != 200) {
            throw new \Exception("The url {$this->url} return the following status: {$responseStatus}.");
        }
        $this->domCrawler->addContent($response->getBody()->getContents());
        $this->domCrawler->filter('.poster .image')->each(function (Crawler $node) {
            $movieName = $node->attr('alt');
            $movieUrl = $this->baseUrl . $node->parents()->attr('data-film-slug');
            $this->movies[] = [
                'name' => $movieName,
                'link' => $movieUrl,
            ];
        });
        $this->domCrawler->clear();
    }

    /**
     * Requests to get year and imdb identifier
     *
     * @return void
     */
    protected function retrieveAndSetOtherData()
    {
        $requests = function ($total) {
            foreach ($this->movies as $movie) {
                yield new Request('GET', $movie['link']);
            }
        };
        $pool = new Pool($this->httpClient, $requests(count($this->movies)), [
            'concurrency' => 20,
            'fulfilled' => function ($response, $index) {
                /** @var Response $response */
                $this->domCrawler->addContent($response->getBody()->getContents());
                $movieYear = $this->domCrawler->filter('#poster-col .film-poster')->attr('data-film-release-year');
                $imdbUrl = $this->domCrawler->filter('a[data-track-action="IMDb"]')->attr('href');
                $imdbId = (preg_match('/.*\/tt([0-9]+)\/maindetails$/', $imdbUrl, $matches)) ? $matches[1] : 0;
                $this->domCrawler->clear();
                $this->movies[$index]['imdb'] = $imdbId;
                $this->movies[$index]['year'] = (int) $movieYear;
                unset($this->movies[$index]['link']);
            },
            'rejected' => function ($reason, $index) {
                unset($this->movies[$index]);
            },
        ]);
        $promise = $pool->promise();
        $promise->wait();
    }
}
