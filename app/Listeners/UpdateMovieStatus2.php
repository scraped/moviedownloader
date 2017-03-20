<?php

namespace App\Listeners;

use App\Events\TorrentAddedToClient;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Transmission\Model\File;

class UpdateMovieStatus2
{
    /**
     * Create the event listener.
     *
     * @return UpdateMovieStatus2
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param  TorrentAddedToClient  $event
     * @return void
     */
    public function handle(TorrentAddedToClient $event)
    {
        $torrent = $event->torrent;
        $torrentName = $torrent->getName();
        logger("Torrent added to client: {$torrentName}");
        $movie = $event->movie;
        $movie->status = 'downloading';
        $movie->torrent_hash = $torrent->getHash();
        $movie->save();
    }
}
