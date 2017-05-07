<?php

namespace App\Listeners;

use App\Events\TorrentDownloadFinished;
use App\Helpers\Torrent\MoviePath;
use App\Mail\TorrentDownloadFinished as MailTorrentDownloadFinished;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

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
        $movieFullName = "{$movie->name} {$movie->year}";
        $movieFileFullPath = $this->getMovieFileFullPath($torrent);
        $subtitleFullPath = preg_replace('/\\.[^.\\s]{3,4}$/', '', $movieFileFullPath) . '.srt';
        // TODO: if subtitle not found try one last thing, get by hash movie file
        $isSubtitleFound = !is_null($movie->subtitle);
        if ($isSubtitleFound) {
            $subtitleContent = gzdecode(file_get_contents($movie->subtitle));
            file_put_contents($subtitleFullPath, $subtitleContent);
            logger("[{$movieFullName}] Subtitle retrieved: {$subtitleFullPath}");
        } else {
            logger("[{$movieFullName}] Subtitle not found: {$subtitleFullPath}");
        }
        $receipts = explode(',', config('moviedownloader.notification.email'));
        Mail::send(new MailTorrentDownloadFinished($event->torrent, $movie, $receipts, $isSubtitleFound));
        logger("[{$movieFullName}] Notification sent: " . config('moviedownloader.notification.email'));
    }
}
