<?php

namespace App\Jobs\TorrentSearchers;

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

interface TorrentSearcherInterface
{

    /**
     * TorrentSearcher constructor.
     *
     * @param Client $httpClient
     * @param Crawler $domCrawler
     */
    public function __construct(Client $httpClient, Crawler $domCrawler);

    /**
     * Search for torrents
     *
     * @param $query
     *
     * @return array
     */
    public function search($query);
}