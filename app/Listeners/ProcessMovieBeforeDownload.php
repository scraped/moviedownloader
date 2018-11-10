<?php

namespace App\Listeners;

use App\Events\MovieCreated;
use App\Jobs\{
    TorrentSearch,
    SubtitleSearch,
    SendTorrentToClient,
    MatchTorrentAndSubtitle,
    ChooseTorrentAndSubtitle
};

class ProcessMovieBeforeDownload
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  MovieCreated  $event
     * @return void
     */
    public function handle(MovieCreated $event)
    {
        $movie = $event->movie;

        TorrentSearch::dispatch($movie)->chain([
            new SubtitleSearch($movie),
            new MatchTorrentAndSubtitle($movie),
            new ChooseTorrentAndSubtitle($movie),
            new SendTorrentToClient($movie),
        ])->allOnQueue('before_download');
    }
}
