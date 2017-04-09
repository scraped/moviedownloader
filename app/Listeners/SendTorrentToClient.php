<?php

namespace App\Listeners;

use App\Events\TorrentAddedToClient;
use \App\Events\TorrentChosen;
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
     * @param  TorrentChosen  $event
     *
     * @return void
     */
    public function handle(TorrentChosen $event)
    {
        $torrentUrl = $event->torrent['url'];
        logger("Torrent chosen: {$torrentUrl}");
        $movie = $event->movie;
        $movie->torrent = $torrentUrl;
        $movie->save();
        /** @var Torrent $torrent */
        $torrent = $this->torrentClient->add($torrentUrl, false, $this->movieFolder);
        event(new TorrentAddedToClient($torrent, $event->movie));
    }
}
