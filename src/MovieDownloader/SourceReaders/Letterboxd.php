<?php

namespace MovieDownloader\SourceReaders;

use Symfony\Component\DomCrawler\Crawler;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Pool;

class Letterboxd extends RemoteMovieProvider
{
    /**
     * @var string
     */
    protected $baseUrl = 'https://letterboxd.com';

    /**
     * @inheritDoc
     */
    public function getMovies(): array
    {
        $page = 1;
        while (true) {
            if (!$this->retrieveName($page)) {
                break;
            }
            $page++;
        }
        $this->retrieveOtherData();

        return $this->movies;
    }

    /**
     * Retrieve name and link for more information.
     *
     * @param integer $page
     * @return bool
     */
    protected function retrieveName(int $page)
    {
        $moviesFound = false;
        $url = "{$this->url}/page/{$page}/";
        $response = $this->httpClient->request('get', $url);
        $this->crawler->addContent($response->getBody()->getContents());
        $this->crawler->filterXPath('//div[contains(@class, "poster")]/img[contains(@class, "image")]')->each(function (Crawler $node) use (&$moviesFound) {
            $this->movies[] = [
                'name' => $node->attr('alt'),
                'link' => "{$this->baseUrl}{$node->parents()->attr('data-film-slug')}",
            ];
            $moviesFound = true;
        });
        $this->crawler->clear();

        return $moviesFound;
    }

    /**
     * Retrieve other data like movie year and imdb identifier.
     *
     * @return void
     */
    protected function retrieveOtherData()
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
                $this->crawler->addContent($response->getBody()->getContents());
                $movieYear = $this->crawler->filter('#js-poster-col .film-poster')->attr('data-film-release-year');
                $imdbUrl = $this->crawler->filter('a[data-track-action="IMDb"]')->attr('href');
                $imdbId = (preg_match('/.*\/tt([0-9]+)\/maindetails$/', $imdbUrl, $matches)) ? $matches[1] : 0;
                $this->crawler->clear();

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
