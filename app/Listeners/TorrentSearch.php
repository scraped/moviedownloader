<?php

namespace App\Listeners;

use App\Events\MovieRetrieved;
use App\Events\TorrentsFound;
use Illuminate\Contracts\Queue\ShouldQueue;
use Xurumelous\TorrentScraper\TorrentScraperService;

class TorrentSearch implements ShouldQueue
{

    /**
     * @var TorrentScraperService
     */
    protected $torrentSearcher;

    /**
     * Create the event listener.
     *
     * @param TorrentScraperService $torrentSearcher
     *
     * @return TorrentSearch
     */
    public function __construct(TorrentScraperService $torrentSearcher)
    {
        $this->torrentSearcher = $torrentSearcher;
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
        $allTorrents = collect($this->torrentSearcher->search($movieFullName))->transform(function ($torrent) {
            $converted = [];
            foreach ((array) $torrent as $key => $value) {
                $converted[preg_match('/^\x00(?:.*?)\x00(.+)/', $key, $matches) ? $matches[1] : $key] = $value;
            }

            return $converted;
        });

        if ($allTorrents->isEmpty()) {
            logger("[{$movieFullName}] None torrent found");
            return;
        }
        event(new TorrentsFound($event->movie, $allTorrents));
    }

}
