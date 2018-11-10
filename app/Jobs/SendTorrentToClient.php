<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Transmission\Transmission;
use Exception;
use App\Movie;
use App\Torrent;

class SendTorrentToClient implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Movie
     */
    protected $movie;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Movie $movie)
    {
        $this->movie = $movie;
    }

    /**
     * Execute the job.
     *
     * @param  Transmission $client
     * @return void
     */
    public function handle(Transmission $client)
    {
        $torrentSelected = $this->movie->torrents()
            ->join('torrent_subtitle', 'torrents.id', '=', 'torrent_subtitle.fk_torrent')
            ->where('status', 'to download')
            ->first();

        if (is_null($torrentSelected)) {
            throw new Exception("Not found the pair torrent and subtitle to start the download of movie {$this->movie->name}.");
        }

        $torrentAdded = $client->add($torrentSelected->magnet_url, false, config('moviedownloader.movie_folder'));

        $torrentSelected->client_hash = $torrentAdded->getHash();
        $torrentSelected->save();
    }
}
