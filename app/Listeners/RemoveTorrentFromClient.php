<?php

namespace App\Listeners;

use App\Events\TorrentDownloadFinished;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Transmission\Transmission;

class RemoveTorrentFromClient
{

    /**
     * @var Transmission
     */
    protected $torrentClient;

    /**
     * Create the event listener.
     *
     * @param  Transmission $torrentClient
     *
     * @return RemoveTorrentFromClient
     */
    public function __construct(Transmission $torrentClient)
    {
        $this->torrentClient = $torrentClient;
    }

    /**
     * Handle the event.
     *
     * @param  TorrentDownloadFinished  $event
     * @return void
     */
    public function handle(TorrentDownloadFinished $event)
    {
        $this->torrentClient->remove($event->torrent);
    }
}
