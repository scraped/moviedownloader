<?php

namespace App\Providers;

use App\Events\MovieRetrieved;
use App\Events\MovieSourceRead;
use App\Events\TorrentAddedToClient;
use App\Events\TorrentDownloadFinished;
use App\Events\TorrentChosen;
use App\Events\TorrentsFound;
use App\Listeners\FilterTorrents;
use App\Listeners\RemoveTorrentFromClient;
use App\Listeners\RetrieveSubtitle;
use App\Listeners\SaveMovies;
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
        MovieSourceRead::class         => [
            SaveMovies::class,
        ],
        MovieRetrieved::class          => [
            TorrentSearch::class,
        ],
        TorrentsFound::class           => [
            FilterTorrents::class,
        ],
        TorrentChosen::class           => [
            SendTorrentToClient::class,
        ],
        TorrentAddedToClient::class    => [
            UpdateMovieStatus2::class,
        ],
        TorrentDownloadFinished::class => [
            UpdateMovieStatus::class,
            RemoveTorrentFromClient::class,
            RetrieveSubtitle::class,
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
