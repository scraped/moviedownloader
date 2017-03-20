<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Transmission\Client;
use Transmission\Transmission as TransmissionApi;

class Transmission extends ServiceProvider
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
        $this->app->singleton(TransmissionApi::class, function($app) {
            $config = config('moviedownloader.transmission');
            $client = new Client($config['host'], $config['port']);
            $client->authenticate($config['username'], $config['password']);
            $transmission = new TransmissionApi();
            $transmission->setClient($client);

            return $transmission;
        });
    }

    public function provides()
    {
        return [
            TransmissionApi::class,
        ];
    }
}
