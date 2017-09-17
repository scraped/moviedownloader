<?php

namespace App\Helpers\Torrent;

use App\Movie;
use Transmission\Model\File;
use Transmission\Model\Torrent;

trait MoviePath
{

    /**
     * Get full file path of movie downloaded
     *
     * @param Movie $movie
     * @param Torrent $torrent
     * @return string
     */
    public function getMovieFileFullPath(Movie $movie, Torrent $torrent)
    {
        $torrentBaseFolder = config('moviedownloader.movie_folder');
        $files = $torrent->getFiles();
        $fileFullPath = '';
        /** @var File $file */
        foreach ($files as $file) {
            $fileName = $file->getName();
            if (preg_match('/\.mp4|avi|mkv|webl|mpg$/', $fileName) !== 0 && $this->checkFileHasAllMovieWords($fileName, $movie->name)) {
                $fileFullPath = "{$torrentBaseFolder}/{$fileName}";
                break;
            }
        }

        return $fileFullPath;
    }

    /**
     * Check if informed file name has all movie name words
     *
     * @param  string $fileName
     * @param  string $movieName
     * @return bool
     */
    public function checkFileHasAllMovieWords($fileName, $movieName)
    {
        preg_match_all('~\w+(?:-\w+)*~', $movieName, $movieNameWords);
        foreach ($movieNameWords[0] as $word) {
            if (strpos(strtolower($fileName), strtolower($word)) === false) {
                return false;
            }
        }

        return true;
    }

}
