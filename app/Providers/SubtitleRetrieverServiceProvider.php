<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use MovieDownloader\SubtitleRetriever;

class SubtitleRetrieverServiceProvider extends ServiceProvider
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
        $this->app->bind(SubtitleRetriever::class, function () {
            return new SubtitleRetriever(config('moviedownloader.movie_folder'));
        });
    }

    /**
     * @inheritDoc
     */
    public function provides()
    {
        return [
            SubtitleRetriever::class,
        ];
    }
}
