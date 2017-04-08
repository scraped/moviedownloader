<?php

namespace App\Providers;

use App\Jobs\TorrentSearchers;
use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\DomCrawler\Crawler;

class TorrentSearcher extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(TorrentSearchers::class, function($app) {
            $torrentSearchers = config('moviedownloader.torrent_searchers');

            return new TorrentSearchers($app[Client::class], $app[Crawler::class], $torrentSearchers);
        });
    }

    public function provides()
    {
        return [
            TorrentSearchers::class,
        ];
    }
}
