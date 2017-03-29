## Description

This projects aims to make torrent download dead simple. The steps are the following:

    1. user inform the wanted movies at his [Letterboxd](https://letterboxd.com/) watchlist
    2. the movie and its subtitle (if found) will be downloaded to the configured folder
    3. when the download terminates the user will receive an e-mail notification

## Installation

You need Virtual box and Vagrant installed before proceed: 

    $ git clone <repo> ./MovieDownloader
    $ read carefuly the file config/moviedownloader.php and custom accordingly the .env file
    $ cd MovieDownloader/vagrant && vagrant up

## External services

The project use two external services. You need to have an account in both:

* Letterboxd: responsible for the list of movies to download. Make you account [here](https://letterboxd.com/).
* Opensubtitles: responsible for the movie subtitles. Make you account [here](https://www.opensubtitles.org/en/newuser).

