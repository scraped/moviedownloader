<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Notification;
use App\Notifications\MovieDownloaded;
use App\Torrent;

class SendNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Torrent
     */
    protected $torrent;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Torrent $torrent)
    {
        $this->torrent = $torrent;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Notification::route('mail', explode(',', config('moviedownloader.notification.email')))
            ->notify(new MovieDownloaded($this->torrent->movie));
    }
}
