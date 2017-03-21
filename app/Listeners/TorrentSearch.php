<?php

namespace App\Listeners;

use \App\Events\MovieRetrieved;
use App\Events\TorrentChosen;
use App\Events\TorrentsFound;
use GuzzleHttp\Client;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Symfony\Component\DomCrawler\Crawler;

class TorrentSearch implements ShouldQueue
{

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
     * Create the event listener.
     *
     * @param  Client $httpClient
     * @param  Crawler $domCrawler
     *
     * @return TorrentSearch
     */
    public function __construct(Client $httpClient, Crawler $domCrawler)
    {
        $this->url = config('moviedownloader.torrent_sources')[0];
        $this->httpClient = $httpClient;
        $this->domCrawler = $domCrawler;
    }

    /**
     * Handle the event.
     *
     * @param  MovieRetrieved  $event
     *
     * @return void
     */
    public function handle(MovieRetrieved $event)
    {
        $movieFullName = "{$event->movie->name} {$event->movie->year}";
        logger("Search for torrent: {$movieFullName}");
        $response = $this->getResponse($movieFullName);
        $allTorrents = $this->retrieveAllTorrents($response);
        if ($allTorrents->isEmpty()) {
            logger("None torrent found: {$movieFullName}");
            return;
        }
        event(new TorrentsFound($event->movie, $allTorrents));
    }

    /**
     * Search for movie at torrent rss
     *
     * @param  string $name
     *
     * @throws \Exception
     *
     * @return string
     */
    protected function getResponse($name)
    {
        $fullUrl = $this->url . rawurlencode($name);
        $response = $this->httpClient->get($fullUrl);
        $responseStatus = $response->getStatusCode();
        if ($responseStatus != 200) {
            throw new \Exception("The url {$fullUrl} return the following status: {$responseStatus}");
        }

        return $response->getBody()->getContents();
    }

    /**
     * Retrieve
     *
     * @param  string $response
     *
     * @return Collection
     */
    protected function retrieveAllTorrents($response)
    {
        $this->domCrawler->addContent($response);
        $results = [];
        $items = $this->domCrawler->filterXPath('//channel/item');
        $titles = $items->filterXPath('//title')->extract(['_text']);
        $urls = $items->filterXPath('//enclosure')->extract(['url']);
        $sizes = $items->filterXPath('//size')->extract(['_text']);
        $seeders = $items->filterXPath('//seeders')->extract(['_text']);
        $leechers = $items->filterXPath('//leechers')->extract(['_text']);
        $infoHash = $items->filterXPath('//info_hash')->extract(['_text']);
        foreach ($titles as $key => $title) {
            $results[] = [
                'title' => $title,
                'url' => $urls[$key],
                'size' => $sizes[$key],
                'seeders' => $seeders[$key],
                'leechers' => $leechers[$key],
                'hash' => $infoHash[$key],
            ];
        }

        return collect($results);
    }
}
