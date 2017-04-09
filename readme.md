## Description

This projects aims to make torrent download dead simple. The steps are the following:

1. You inform the wanted movies at your [Letterboxd](https://letterboxd.com/) watchlist
2. the movie and its subtitle (if found) will be downloaded to the configured folder
3. when the download terminates you will receive an e-mail notification

## Installation

You need Virtual box and Vagrant installed before proceed: 

    $ git clone https://github.com/gustavobgama/moviedownloader.git ./MovieDownloader
    $ read carefuly the file config/moviedownloader.php and custom accordingly the .env file
    $ cd MovieDownloader/vagrant && vagrant up

You can check the download progress of transmission (torrent client) at [http://192.168.10.90:9091](http://192.168.10.90:9091)

## External services

The project use two external services. You need to have an account in both:

* Letterboxd: responsible for the list of movies to download. Make you account [here](https://letterboxd.com/).
* Opensubtitles: responsible for the movie subtitles. Make you account [here](https://www.opensubtitles.org/en/newuser).

## Tanks to

The movie downloader is built on top of great tools like...

* [Laravel](https://laravel.com/)
* [Transmission](https://transmissionbt.com/)
* [Redis](https://redis.io/)
* [PHP Transmission API](https://github.com/kleiram/transmission-php)

and many others...

