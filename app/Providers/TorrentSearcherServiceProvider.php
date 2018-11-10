<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Lerta\TorrentScraper\TorrentScraper;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class TorrentSearcherServiceProvider extends ServiceProvider
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
        $this->app->bind(TorrentScraper::class, function () {
            // $torrentSearchers = config('moviedownloader.torrent_searchers');
            // if (empty($torrentSearchers)) {
            //     throw new \Exception('Missing torrent searchers, please check the configuration.');
            // }
            $adapters = [
                'ThePirateBay' => [
                    'base_url' => 'https://thepiratebay.org/',
                    'proxy_list' => 'https://thepiratebay-proxylist.org/',
                ]
            ];

            return new TorrentScraper($adapters, resolve(Client::class), resolve(Crawler::class));
        });
    }

    /**
     * @inheritDoc
     */
    public function provides()
    {
        return [
            TorrentScraper::class,
        ];
    }
}
