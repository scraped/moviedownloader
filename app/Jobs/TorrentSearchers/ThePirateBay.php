<?php

namespace App\Jobs\TorrentSearchers;

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class ThePirateBay extends TorrentSearcher implements TorrentSearcherInterface
{

    const SEARCH_URL = 'https://thepiratebay.se/search/%s/0/7/0';

    /**
     * ThePirateBay constructor.
     *
     * @param Client $httpClient
     * @param Crawler $domCrawler
     */
    public function __construct(Client $httpClient, Crawler $domCrawler)
    {
        parent::__construct($httpClient, $domCrawler);
    }

    public function search($query)
    {
        $this->torrents = [];
        $response = $this->httpClient->get(sprintf(static::SEARCH_URL, $query));
        if ($response->getStatusCode() != 200) {
            throw new \Exception('');
        }
        $this->domCrawler->clear();
        $this->domCrawler->addContent($response->getBody()->getContents());
        $this->domCrawler->filter('#searchResult tr:not(.header)')->each(function(Crawler $node) {
            $description = $node->filter('.detDesc')->text();
            $torrentSize = (preg_match('/.*Size\ ([0-9]+\.[0-9]+).*([G|M]iB)/', $description, $matches)) ? $matches[1] : 0;
            $torrentSizeUnit = (isset($matches[2])) ? $matches[2] : 'MiB';
            $torrentSizeConvertedToMib = ($torrentSizeUnit === 'GiB') ? $torrentSize * 1024 : $torrentSize;
            $this->torrents[] = [
                'title' => $node->filter('.detName a')->text(),
                'url' => $node->filter('td a[title="Download this torrent using magnet"]')->attr('href'),
                'size' => $torrentSizeConvertedToMib,
                'seeders' => $node->filter('td')->eq(2)->text(),
                'leechers' => $node->filter('td')->eq(3)->text(),
            ];
        });

        return $this->torrents;
    }
}