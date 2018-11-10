<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use MovieDownloader\SourceReaders\RemoteMovieProvider;
use MovieDownloader\SourceReaders\Letterboxd;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class SourceReaderServiceProvider extends ServiceProvider
{
    /**
     * @inheritDoc
     */
    protected $defer = true;

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(RemoteMovieProvider::class, function () {
            // build based on config
            return new Letterboxd(
                resolve(Client::class),
                resolve(Crawler::class),
                'https://letterboxd.com/gustavobgama/watchlist'
            );
        });
    }

    /**
     * @inheritDoc
     */
    public function provides()
    {
        return [
            RemoteMovieProvider::class,
        ];
    }
}
