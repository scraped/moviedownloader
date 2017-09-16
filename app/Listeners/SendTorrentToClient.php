<?php

namespace App\Listeners;

use App\Events\TorrentAddedToClient;
use Illuminate\Contracts\Queue\ShouldQueue;
use Transmission\Model\Torrent;
use Transmission\Transmission;
use \App\Events\TorrentChosen;

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
        $torrentUrl = $event->torrent['magnetUrl'];
        $movie = $event->movie;
        $movieFullName = "{$movie->name} {$movie->year}";
        logger("[{$movieFullName}] Torrent chosen: {$torrentUrl}");
        $movie->torrent = $torrentUrl;
        $movie->save();
        /** @var Torrent $torrent */
        $torrent = $this->torrentClient->add($torrentUrl, false, $this->movieFolder);
        event(new TorrentAddedToClient($torrent, $event->movie));
    }
}
