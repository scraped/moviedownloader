<?php

namespace MovieDownloader\SourceReaders;

use Symfony\Component\DomCrawler\Crawler;
use GuzzleHttp\Client;

abstract class RemoteMovieProvider
{
    /**
     * @var Client
     */
    protected $httpClient;

    /**
     * @var Crawler
     */
    protected $crawler;

    /**
     * @var string
     */
    protected $url;

    /**
     * @var array
     */
    protected $movies;

    /**
     * Constructor.
     *
     * @param Client $httpClient
     * @param Crawler $crawler
     * @param string $url
     */
    public function __construct(Client $httpClient, Crawler $crawler, string $url)
    {
        $this->httpClient = $httpClient;
        $this->crawler = $crawler;
        $this->url = $url;
        $this->movies = [];
    }

    /**
     * Get movies.
     *
     * @return array
     */
    abstract public function getMovies(): array;
}
