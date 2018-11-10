<?php

namespace MovieDownloader\Torrent;

trait MoviePath
{
    /**
     * Get partial file path of movie informed.
     *
     * @param  string $movieName
     * @param  array $files
     * @return string
     */
    public function getMovieFilePath(string $movieName, array $files)
    {
        $filePath = '';
        foreach ($files as $file) {
            if (preg_match('/\.mp4|avi|mkv|webl|mpg$/', $file) !== 0 && $this->checkFileHasAllMovieWords($file, $movieName)) {
                $filePath = $file;
                break;
            }
        }

        return $filePath;
    }

    /**
     * Check if informed file name has all movie name words.
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
