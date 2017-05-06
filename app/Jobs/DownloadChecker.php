<?php

namespace App\Jobs;

use App\Events\TorrentDownloadFinished;
use App\Helpers\Torrent\MoviePath;
use App\Movie;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Transmission\Model\Torrent;
use Transmission\Transmission;

class DownloadChecker implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, MoviePath;

    /**
     * @var Transmission
     */
    protected $torrentClient;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Transmission $torrentClient)
    {
        $this->torrentClient = $torrentClient;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $movies = Movie::where('status', 'downloading')->get();
        /** @var Movie $movie */
        foreach ($movies as $movie) {
            $hash = $movie->torrent_hash;
            try {
                /** @var Torrent $torrent */
                $torrent = $this->torrentClient->get($hash);
            } catch (\Exception $e) {
                $movie->status = 'failed';
                $movie->save();
                continue;
            }
            if (!$torrent->isFinished()) {
                continue;
            }
            $movieFileFullPath = $this->getMovieFileFullPath($torrent);
            $movieFullName = "{$movie->name} {$movie->year}";
            logger("[{$movieFullName}] Torrent download finished: {$movieFileFullPath}");
            event(new TorrentDownloadFinished($torrent, $movie));
        }

    }
}
