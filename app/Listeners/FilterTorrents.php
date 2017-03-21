<?php

namespace App\Listeners;

use App\Events\TorrentChosen;
use App\Events\TorrentsFound;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class FilterTorrents implements ShouldQueue
{

    /**
     * @var int
     */
    protected $maxSize;

    /**
     * Create the event listener.
     *
     * @return FilterTorrents
     */
    public function __construct()
    {
        $this->maxSize = config('moviedownloader.torrent_filters.max_size');
    }

    /**
     * Handle the event.
     *
     * @param  TorrentsFound  $event
     *
     * @return void
     */
    public function handle(TorrentsFound $event)
    {
        $movieFullName = "{$event->movie->name} {$event->movie->year}";
        $allTorrents = $event->torrents;
        $quantityOfTorrents = $allTorrents->count();
        logger("{$quantityOfTorrents} torrents found: {$movieFullName}");
        $filteredTorrent = $allTorrents->where('size', '<', $this->maxSize)
            ->sortByDesc('seeders')
            ->first();
        if (empty($filteredTorrent)) {
            logger("After filtering no torrent left. Max size: {$this->maxSize}");
            return;
        }
        event(new TorrentChosen($event->movie, $filteredTorrent));
    }

}
