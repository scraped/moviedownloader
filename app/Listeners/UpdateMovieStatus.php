<?php

namespace App\Listeners;

use App\Events\TorrentDownloadFinished;
use App\Mail\TorrentDownloadFinished as MailTorrentDownloadFinished;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class UpdateMovieStatus
{
    /**
     * Create the event listener.
     *
     * @return UpdateMovieStatus
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  TorrentDownloadFinished  $event
     *
     * @return void
     */
    public function handle(TorrentDownloadFinished $event)
    {
        $movie = $event->movie;
        $movie->status = 'done';
        $movie->save();

        $receipts = explode(',', config('moviedownloader.notification.email'));
        Mail::send(new MailTorrentDownloadFinished($event->torrent, $movie, $receipts));
    }
}
