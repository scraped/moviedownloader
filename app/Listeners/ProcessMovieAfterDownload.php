<?php

namespace App\Listeners;

use App\Events\TorrentDownloaded;
use App\Jobs\{
    RetrieveSubtitle,
    SendNotification
};

class ProcessMovieAfterDownload
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
     * @param  TorrentDownloaded  $event
     * @return void
     */
    public function handle(TorrentDownloaded $event)
    {
        RetrieveSubtitle::dispatch($event->torrent, $event->files)->chain([
            new SendNotification($event->torrent),
        ])->allOnQueue('after_download');
    }
}
