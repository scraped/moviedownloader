<?php

namespace App\Listeners;

use \App\Events\MovieRetrieved;
use App\Events\TorrentChosen;
use App\Events\TorrentsFound;
use App\Jobs\TorrentSearchers;
use GuzzleHttp\Client;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Symfony\Component\DomCrawler\Crawler;
use Xurumelous\TorrentScraper\Entity\SearchResult;
use Xurumelous\TorrentScraper\TorrentScraperService;

class TorrentSearch implements ShouldQueue
{

    /**
     * @var TorrentSearchers
     */
    protected $torrentSearchers;

    /**
     * Create the event listener.
     *
     * @param  TorrentSearchers $torrentSearchers
     *
     * @return TorrentSearch
     */
    public function __construct(TorrentSearchers $torrentSearchers)
    {
        $this->torrentSearchers = $torrentSearchers;
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
        logger("[{$movieFullName}] Search for torrent");
        $allTorrents = collect($this->torrentSearchers->search($movieFullName));
        if ($allTorrents->isEmpty()) {
            logger("[{$movieFullName}] None torrent found");
            return;
        }
        event(new TorrentsFound($event->movie, $allTorrents));
    }

}
