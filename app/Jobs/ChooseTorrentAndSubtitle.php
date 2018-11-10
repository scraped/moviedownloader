<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\{
    Movie,
    TorrentSubtitle
};

class ChooseTorrentAndSubtitle implements ShouldQueue
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
     * @return void
     */
    public function handle()
    {
        TorrentSubtitle::whereIn('fk_torrent', $this->movie->torrents->pluck('id'))
            ->orderBy('score', 'desc')
            ->first()
            ->update(['status' => 'to download']);
    }
}
