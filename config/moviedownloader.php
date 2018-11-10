<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Letterboxd watch list url
    |--------------------------------------------------------------------------
    |
    | Here you define your letterboxd watchlist url. If you don't have one
    | account go here and make one: https://letterboxd.com/
    |
    */

    'letterboxd' => [
        'watchlist_url' => env('LETTERBOXD_WATCHLIST_URL', 'https://letterboxd.com/username/watchlist/'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Transmission connection
    |--------------------------------------------------------------------------
    |
    | Probably you will not need to change none of these settings, specially if
    | you were using the vagrant.
    |
    */
    'transmission' => [
        'host' => env('TRANSMISSION_HOST', 'transmission'),
        'port' => env('TRANSMISSION_PORT', 9091),
        'username' => env('TRANSMISSION_USERNAME', 'admin'),
        'password' => env('TRANSMISSION_PASSWORD', 'password'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Torrent searchers engines
    |--------------------------------------------------------------------------
    |
    | Possible values: thePirateBay and kickassTorrents
    */
    'torrent_searchers' => [
        'thePirateBay',
    ],

    /*
    |--------------------------------------------------------------------------
    | Filters used to choose what torrent download
    |--------------------------------------------------------------------------
    |
    | Filters to choose the torrent that fits to your needs.
    | * max_size defined in MiB
    |
    */
    'torrent_filters' => [
        'max_size' => env('TORRENT_FILTER_MAX_SIZE', 1200),
    ],

    /*
    |--------------------------------------------------------------------------
    | Opensubtitles connection to download subtitles
    |--------------------------------------------------------------------------
    |
    | Here, inform your opensubtitle account. If you don't have one, create one
    | here: https://www.opensubtitles.org/en/newuser .For language codes use
    | this reference http://www.opensubtitles.org/addons/export_languages.php
    |
    */
    'opensubtitles' => [
        'username' => env('OPENSUBTITLE_USERNAME', 'username'),
        'password' => env('OPENSUBTITLE_PASSWORD', 'password'),
        'language' => env('OPENSUBTITLE_LANGUAGE', 'pob'),
        'useragent' => env('OPENSUBTITLE_USERAGENT', 'TemporaryUserAgent'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Notification when movie is downloaded
    |--------------------------------------------------------------------------
    |
    | A notification will be sent when the movie finish download.
    |
    */
    'notification' => [
        'email' => env('NOTIFICATION_EMAIL', 'example1@example.com,example2@example.com'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Folder for movies and subtitles
    |--------------------------------------------------------------------------
    |
    | Folder where movies and subtitles will be saved.
    |
    */
    'movie_folder' => '/data/movies',
];
