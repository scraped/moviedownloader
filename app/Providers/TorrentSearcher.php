<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Xurumelous\TorrentScraper\TorrentScraperService;

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
        $this->app->bind(TorrentScraperService::class, function ($app) {
            $torrentSearchers = config('moviedownloader.torrent_searchers');
            if (empty($torrentSearchers)) {
                throw new \Exception("Missing torrent searchers, please check the configuration.");
            }

            return new TorrentScraperService($torrentSearchers);
        });
    }

    public function provides()
    {
        return [
            TorrentScraperService::class,
        ];
    }
}
