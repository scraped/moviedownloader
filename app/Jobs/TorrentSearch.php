<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Movie;
use Exception;
use Lerta\TorrentScraper\TorrentScraper;

class TorrentSearch implements ShouldQueue
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
     * @param  TorrentScraper $searcher
     * @return void
     */
    public function handle(TorrentScraper $searcher)
    {
        $torrents = $searcher->search($this->movie->full_name);
        if (empty($torrents)) {
            throw new Exception("No torrent found for movie: {$this->movie->full_name}.");
        }

        foreach ($torrents as $torrent) {
            $this->movie->torrents()->create([
                'name' => $torrent['name'],
                'seeders' => $torrent['seeders'],
                'leechers' => $torrent['leechers'],
                'magnet_url' => $torrent['magnet_url'],
                'size' => $torrent['size'],
            ]);
        }
    }
}
