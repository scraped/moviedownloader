<?php

namespace App\Listeners;

use App\Events\TorrentAddedToClient;
use \App\Events\TorrentFound;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Transmission\Model\Torrent;
use Transmission\Transmission;

class SendTorrentToClient implements ShouldQueue
{

    /**
     * @var Transmission
     */
    protected $torrentClient;

    /**
     * @var string
     */
    protected $movieFolder;

    /**
     * Create the event listener.
     *
     * @param  Transmission $torrentClient
     *
     * @return SendTorrentToClient
     */
    public function __construct(Transmission $torrentClient)
    {
        $this->torrentClient = $torrentClient;
        $this->movieFolder = config('moviedownloader.movie_folder');
    }

    /**
     * Handle the event.
     *
     * @param  TorrentFound  $event
     *
     * @return void
     */
    public function handle(TorrentFound $event)
    {
        $torrentUrl = $event->torrent['url'];
        logger("Torrent found: {$torrentUrl}");
        /** @var Torrent $torrent */
        $torrent = $this->torrentClient->add($torrentUrl, false, $this->movieFolder);
        event(new TorrentAddedToClient($torrent, $event->movie));
    }
}