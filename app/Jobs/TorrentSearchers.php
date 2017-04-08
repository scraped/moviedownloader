<?php

namespace App\Jobs;

use App\Jobs\TorrentSearchers\ThePirateBay;
use App\Jobs\TorrentSearchers\TorrentSearcherInterface;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class TorrentSearchers
{
    const THEPIRATEBAY = 'thepiratebay';

    /**
     * @var array
     */
    protected $torrentSearchers;

    public function __construct(Client $httpClient, Crawler $domCrawler, array $torrentSearcherNames)
    {
        // TODO: assemble class name and load dynamically
        $this->torrentSearchers[] = new ThePirateBay($httpClient, $domCrawler);
    }

    public function search($query)
    {
        $result = [];
        /** @var TorrentSearcherInterface $torrentSearcher */
        foreach ($this->torrentSearchers as $torrentSearcher) {
            $result = $torrentSearcher->search($query);
        }

        return $result;
    }
}