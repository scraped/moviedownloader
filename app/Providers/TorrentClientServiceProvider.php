<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Transmission\{
    Transmission,
    Client
};

class TorrentClientServiceProvider extends ServiceProvider
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
        $this->app->singleton(Transmission::class, function () {
            $config = config('moviedownloader.transmission');

            $client = new Client($config['host'], $config['port']);
            $client->authenticate($config['username'], $config['password']);
            $transmission = new Transmission();
            $transmission->setClient($client);

            return $transmission;
        });
    }

    /**
     * @inheritDoc
     */
    public function provides()
    {
        return [
            Transmission::class,
        ];
    }
}
