<?php

namespace App\Console\Commands;

use App\Events\TorrentDownloadFinished;
use App\Movie;
use Illuminate\Console\Command;
use Transmission\Model\File;
use Transmission\Model\Torrent;
use Transmission\Transmission;

class DownloadChecker extends Command
{
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
                // torrent not found at client
            }
            if (!$torrent->isFinished()) {
                continue;
            }
            $movieFileFullPath = $this->getMovieFileFullPath($torrent);
            logger("Torrent download finished: {$movieFileFullPath}");
            event(new TorrentDownloadFinished($torrent, $movie));
        }
    }

    protected function getMovieFileFullPath(Torrent $torrent)
    {
        $torrentBaseFolder = config('moviedownloader.movie_folder');
        $files = $torrent->getFiles();
        $fileFullPath = '';
        /** @var File $file */
        foreach ($files as $file) {
            $fileName = $file->getName();
            if (preg_match('/.*\.[mp4|avi|mkv]/', $fileName)) {
                $fileFullPath = "{$torrentBaseFolder}/{$fileName}";
                break;
            }
        }

        return $fileFullPath;
    }
}
