<?php

namespace App\Jobs\TorrentSearchers;

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

abstract class TorrentSearcher
{

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
    protected $torrents;

    /**
     * TorrentSearcher constructor.
     *
     * @param Client $httpClient
     * @param Crawler $domCrawler
     */
    public function __construct(Client $httpClient, Crawler $domCrawler)
    {
        $this->httpClient = $httpClient;
        $this->domCrawler = $domCrawler;
        $this->domCrawler->clear();
        $this->torrents = [];
    }
}