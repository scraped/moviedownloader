<?php

namespace MovieDownloader;

use MovieDownloader\Torrent\MoviePath;

class SubtitleRetriever
{
    use MoviePath;

    /**
     * @var string
     */
    protected $torrentBaseFolder;

    /**
     * Constructor.
     *
     * @param string $torrentBaseFolder
     */
    public function __construct(string $torrentBaseFolder)
    {
        $this->torrentBaseFolder = $torrentBaseFolder;
    }

    /**
     * Save the subtitle with the same movie file name.
     *
     * @param string $subtitleUrl
     * @param string $movieName
     * @param array $files
     * @return void
     */
    public function save(string $subtitleUrl, string $movieName, array $files)
    {
        $movieFileFullPath = "{$this->torrentBaseFolder}/{$this->getMovieFilePath($movieName, $files)}";
        $subtitleFullPath = preg_replace('/\\.[^.\\s]{3,4}$/', '', $movieFileFullPath) . '.srt';
        // TODO: try/catch the file_get_contents
        $subtitleContent = gzdecode(file_get_contents($subtitleUrl));
        // $subtitleContent = '';

        file_put_contents($subtitleFullPath, $subtitleContent);
    }
}
