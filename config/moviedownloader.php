<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Letterboxd watch list url
    |--------------------------------------------------------------------------
    |
    | Here you define your letterboxd watchlist url. If you don't have one,
    | go here and make one: https://letterboxd.com/
    |
    */

    'letterboxd' => [
        'watchlist_url' => env('LETTERBOXD_WATCHLIST_URL', 'https://letterboxd.com/gustavobgama/watchlist/'),
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
        'host' => env('TRANSMISSION_HOST', '127.0.0.1'),
        'port' => env('TRANSMISSION_PORT', 9091),
        'username' => env('TRANSMISSION_USERNAME', 'transmission'),
        'password' => env('TRANSMISSION_PASSWORD', 'transmission'),
    ],

    'torrent_sources' => [
        'http://extratorrent.cc/rss.xml?type=search&cid=4&search=',
    ],

    /*
    |--------------------------------------------------------------------------
    | Filters used to choose what torrent download
    |--------------------------------------------------------------------------
    |
    | Filters to choose the torrent that fits to your needs.
    |
    */
    'torrent_filters' => [
        'max_size' => env('TORRENT_FILTER_MAX_SIZE', 1500000000),
    ],

    /*
    |--------------------------------------------------------------------------
    | Opensubtitles connection to download subtitles
    |--------------------------------------------------------------------------
    |
    | Here, inform your opensubtitle account. For language codes use this
    | reference http://www.opensubtitles.org/addons/export_languages.php
    |
    */
    'opensubtitles' => [
        'username' => env('OPENSUBTITLE_USERNAME', 'username'),
        'password' => env('OPENSUBTITLE_PASSWORD', 'password'),
        'language' => env('OPENSUBTITLE_LANGUAGE', 'pob'),
        'user_agent' => env('OPENSUBTITLE_USER_AGENT', 'OSTestUserAgentTemp'),
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
    'movie_folder' => env('MOVIE_FOLDER', '/opt/moviedownloader'),

];