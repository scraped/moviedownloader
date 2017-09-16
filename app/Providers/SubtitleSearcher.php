<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Kminek\OpenSubtitles\Client;

class SubtitleSearcher extends ServiceProvider
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
        $this->app->bind(Client::class, function ($app) {
            $config = config('moviedownloader.opensubtitles');
            unset($config['language']);

            return Client::create($config);
        });
    }

    public function provides()
    {
        return [
            Client::class,
        ];
    }
}
