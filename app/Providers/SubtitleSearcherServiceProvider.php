<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Kminek\OpenSubtitles\Client;

class SubtitleSearcherServiceProvider extends ServiceProvider
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
        $this->app->bind(Client::class, function () {
            $config = config('moviedownloader.opensubtitles');
            unset($config['language']);

            return Client::create($config);
        });
    }

    /**
     * @inheritDoc
     */
    public function provides()
    {
        return [
            Client::class,
        ];
    }
}
