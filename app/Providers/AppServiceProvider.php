<?php

namespace App\Providers;

use App\Jobs\MovieSourceReader;
use Illuminate\Support\ServiceProvider;

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
        $this->app->singleton(MovieSourceReader::class, function($app) {
            $url = config('moviedownloader.letterboxd.watchlist_url');

            return new MovieSourceReader($url);
        });
    }

    /**
     * @return array
     */
    public function provides()
    {
        return [
            MovieSourceReader::class,
        ];
    }
}
