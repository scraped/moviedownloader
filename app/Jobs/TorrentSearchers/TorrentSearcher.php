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
        $this->torrents = [];
    }

    /**
     * Get tags
     *
     * @param  string $torrentTitle
     *
     * @return array
     */
    protected function getTags($torrentTitle)
    {
        $supportedTags = [
            'BluRay' => ['BluRay', 'BRRip'],
            'Cam' => ['Cam'],
            'DVB' => ['DVB'],
            'DVD' => ['DVD'],
            'HD-DVD' => ['HD-DVD'],
            'HDTV' => ['HDTV'],
            'PPV' => ['PPV'],
            'Telecine' => ['Telecine'],
            'Telesync'=> ['Telesync'],
            'TV' => ['TV'],
            'VHS' => ['VHS'],
            'VOD' => ['VOD'],
            'WEB-DL' => ['WEB-DL'],
            'WEBRip' => ['WEBRip'],
            'Workprint' => ['Workprint'],
        ];
        $tagsFound = [];
        foreach ($supportedTags as $identifier => $tags) {
            foreach ($tags as $tag) {
                if (stripos($torrentTitle, $tag) !== false) {
                    $tagsFound[] = $identifier;
                }
            }
        }

        return $tagsFound;
    }
}