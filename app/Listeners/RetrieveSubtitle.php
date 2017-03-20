<?php

namespace App\Listeners;

use App\Events\TorrentDownloadFinished;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use OpenSubtitlesApi\FileGenerator;
use OpenSubtitlesApi\SubtitlesManager;
use Transmission\Model\File;

class RetrieveSubtitle implements ShouldQueue
{

    /**
     * @var SubtitlesManager
     */
    protected $subtitleManager;

    /**
     * @var FileGenerator
     */
    protected $fileGenerator;

    /**
     * @var string
     */
    protected $torrentBaseFolder;

    /**
     * Create the event listener.
     *
     * @param  SubtitlesManager $subtitlesManager
     * @param  FileGenerator $fileGenerator
     *
     * @return RetrieveSubtitle
     */
    public function __construct(SubtitlesManager $subtitlesManager, FileGenerator $fileGenerator)
    {
        $this->subtitleManager = $subtitlesManager;
        $this->fileGenerator = $fileGenerator;
        $this->torrentBaseFolder = config('moviedownloader.movie_folder');
    }

    /**
     * Handle the event.
     *
     * @param  TorrentDownloadFinished  $event
     * @return void
     */
    public function handle(TorrentDownloadFinished $event)
    {
        $torrent = $event->torrent;
        $files = $torrent->getFiles();
        $fileFullPath = '';
        /** @var File $file */
        foreach ($files as $file) {
            $fileName = $file->getName();
            if (preg_match('/.*\.[mp4|avi|mkv]/', $fileName)) {
                $fileFullPath = "{$this->torrentBaseFolder}{$fileName}";
                break;
            }
        }
        logger("Torrent download finished: {$fileFullPath}");
        $subtitles = $this->subtitleManager->get($fileFullPath);
        if (!empty($subtitles) && !empty($subtitles[0])) {
            $fileGenerator = new FileGenerator();
            $fileGenerator->downloadSubtitle($subtitles[0], $fileFullPath);
            logger("Subtitle retrieved: {$fileFullPath}");
        } else {
            // subtitle not found
        }
    }
}
