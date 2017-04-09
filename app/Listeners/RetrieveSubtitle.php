<?php

namespace App\Listeners;

use App\Events\TorrentDownloadFinished;
use App\Helpers\Torrent\MoviePath;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use OpenSubtitlesApi\FileGenerator;
use OpenSubtitlesApi\SubtitlesManager;
use App\Mail\TorrentDownloadFinished as MailTorrentDownloadFinished;

class RetrieveSubtitle implements ShouldQueue
{
    use MoviePath;

    /**
     * @var string
     */
    protected $torrentBaseFolder;

    /**
     * Create the event listener.
     *
     * @return RetrieveSubtitle
     */
    public function __construct()
    {
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
        $movie = $event->movie;
        $movieFileFullPath = $this->getMovieFileFullPath($torrent);
        $subtitleFullPath = preg_replace('/\\.[^.\\s]{3,4}$/', '', $movieFileFullPath) . '.srt';
        // TODO: if subtitle not found try one last thing, get by hash movie file
        $isSubtitleFound = !is_null($movie->subtitle);
        if ($isSubtitleFound) {
            $subtitleContent = gzdecode(file_get_contents($movie->subtitle));
            file_put_contents($subtitleFullPath, $subtitleContent);
            logger("Subtitle retrieved: {$subtitleFullPath}");
        } else {
            logger("Subtitle not found: {$subtitleFullPath}");
        }
        $receipts = explode(',', config('moviedownloader.notification.email'));
        Mail::send(new MailTorrentDownloadFinished($event->torrent, $movie, $receipts, $isSubtitleFound));
        logger("Notification sent: " . config('moviedownloader.notification.email'));
    }
}
