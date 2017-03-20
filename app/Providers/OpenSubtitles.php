<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use OpenSubtitlesApi\SubtitlesManager;

class OpenSubtitles extends ServiceProvider
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
        $this->app->singleton(SubtitlesManager::class, function($app) {
            return new SubtitlesManager('gustavobgama', 'hidib', 'pob');
        });
    }

    public function provides()
    {
        return [
            SubtitlesManager::class,
        ];
    }
}