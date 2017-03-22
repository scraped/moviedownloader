<?php

namespace App\Helpers\Torrent;

use Transmission\Model\File;
use Transmission\Model\Torrent;

trait MoviePath
{

    /**
     * Get full file path of movie downloaded
     *
     * @param Torrent $torrent
     *
     * @return string
     */
    public function getMovieFileFullPath(Torrent $torrent)
    {
        $torrentBaseFolder = config('moviedownloader.movie_folder');
        $files = $torrent->getFiles();
        $fileFullPath = '';
        /** @var File $file */
        foreach ($files as $file) {
            $fileName = $file->getName();
            if (preg_match('/\.mp4|avi|mkv|webl|mpg$/', $fileName)) {
                $fileFullPath = "{$torrentBaseFolder}/{$fileName}";
                break;
            }
        }

        return $fileFullPath;
    }

}