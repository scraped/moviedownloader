<?php

namespace App\Providers;

use App\Jobs\ReadMovieSource;
use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\DomCrawler\Crawler;

class AppServiceProvider extends ServiceProvider
{

    protected $defer = true;

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ReadMovieSource::class, function($app) {
            $url = config('moviedownloader.letterboxd.watchlist_url');

            return new ReadMovieSource($url);
        });
    }

    /**
     * @return array
     */
    public function provides()
    {
        return [
            ReadMovieSource::class,
        ];
    }
}
