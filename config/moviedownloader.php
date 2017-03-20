<?php

return [

    'letterboxd' => [
        'base_url' => 'https://letterboxd.com/',
        'watchlist_url' => env('LETTERBOXD_WATCHLIST_URL', 'https://letterboxd.com/gustavobgama/watchlist/'),
    ],

    'transmission' => [
        'host' => env('TRANSMISSION_HOST', '127.0.0.1'),
        'port' => env('TRANSMISSION_PORT', 9091),
        'username' => env('TRANSMISSION_USERNAME', 'transmission'),
        'password' => env('TRANSMISSION_PASSWORD', 'transmission'),
    ],

    'torrent_filter' => [
        'max_size' => 1500000000,
    ],

    'torrent_rss' => 'http://extratorrent.cc/rss.xml?type=search&cid=4&search=',

    'subtitle_language' => 'pob',

    'movie_folder' => env('MOVIE_FOLDER', '/vagrant'),

];