<?php

namespace App\Listeners;

use App\Events\TorrentDownloadFinished;
use App\Movie;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateMovieStatus
{
    /**
     * Create the event listener.
     *
     * @return UpdateMovieStatus
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  TorrentDownloadFinished  $event
     *
     * @return void
     */
    public function handle(TorrentDownloadFinished $event)
    {
        /** @var Movie $movie */
        $movie = $event->movie;
        $movie->status = 'done';
        $movie->save();
    }
}
