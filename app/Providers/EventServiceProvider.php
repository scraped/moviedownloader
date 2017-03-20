<?php

namespace App\Providers;

use App\Events\MovieRetrieved;
use App\Events\TorrentAddedToClient;
use App\Events\TorrentDownloadFinished;
use App\Events\TorrentFound;
use App\Listeners\RemoveTorrentFromClient;
use App\Listeners\RetrieveSubtitle;
use App\Listeners\SendTorrentToClient;
use App\Listeners\TorrentSearch;
use App\Listeners\UpdateMovieStatus;
use App\Listeners\UpdateMovieStatus2;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        MovieRetrieved::class => [
            TorrentSearch::class,
        ],
        TorrentFound::class => [
            SendTorrentToClient::class,
        ],
        TorrentDownloadFinished::class => [
            UpdateMovieStatus::class,
            RemoveTorrentFromClient::class,
            RetrieveSubtitle::class,
        ],
        TorrentAddedToClient::class => [
            UpdateMovieStatus2::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
