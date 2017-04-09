<?php

namespace App\Console\Commands;

use App\Events\TorrentDownloadFinished;
use App\Helpers\Torrent\MoviePath;
use App\Movie;
use Illuminate\Console\Command;
use Transmission\Model\Torrent;
use Transmission\Transmission;

class DownloadChecker extends Command
{
    use MoviePath;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'download:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check the download progress of torrents at its client';

    /**
     * Create a new command instance.
     *
     * @return DownloadChecker
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @param  Transmission $torrentClient
     *
     * @return mixed
     */
    public function handle(Transmission $torrentClient)
    {
        $movies = Movie::where('status', 'downloading')->get();
        /** @var Movie $movie */
        foreach ($movies as $movie) {
            $hash = $movie->torrent_hash;
            try {
                /** @var Torrent $torrent */
                $torrent = $torrentClient->get($hash);
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
