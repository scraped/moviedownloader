<?php

namespace App\Providers;

use App\Jobs\SubtitleSearchers;
use Illuminate\Support\ServiceProvider;

class SubtitleSearcher extends ServiceProvider
{
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
        $this->app->bind(SubtitleSearchers::class, function($app) {
            $config = config('moviedownloader.subtitle_searchers');

            return new SubtitleSearchers($config);
        });
    }

    public function provides()
    {
        return [
            SubtitleSearchers::class,
        ];
    }
}
