<?php

namespace App\Listeners;

use App\Events\MovieRetrieved;
use App\Events\TorrentsAndSubtitlesFound;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Kminek\OpenSubtitles\Client;
use Xurumelous\TorrentScraper\TorrentScraperService;

class TorrentAndSubtitleSearch implements ShouldQueue
{

    /**
     * @var TorrentScraperService
     */
    protected $torrentSearcher;

    /**
     * @var Client
     */
    protected $subtitleSearcher;

    /**
     * Create the event listener.
     *
     * @param TorrentScraperService $torrentSearcher
     * @param Client $subtitleSearcher
     *
     * @return TorrentAndSubtitleSearch
     */
    public function __construct(TorrentScraperService $torrentSearcher, Client $subtitleSearcher)
    {
        $this->torrentSearcher = $torrentSearcher;
        $this->subtitleSearcher = $subtitleSearcher;
    }

    /**
     * Handle the event.
     *
     * @param  MovieRetrieved  $event
     * @return void
     */
    public function handle(MovieRetrieved $event)
    {
        $movieFullName = "{$event->movie->name} {$event->movie->year}";
        logger("[{$movieFullName}] Search for torrents");
        $allTorrents = $this->retrieveTorrents($movieFullName);
        if ($allTorrents->isEmpty()) {
            logger("[{$movieFullName}] None torrent found");
            return;
        } else {
            logger("[{$movieFullName}] {$allTorrents->count()} torrents found");
        }
        logger("[{$movieFullName}] Search for subtitles");
        $allSubtitles = $this->retrieveSubtitles($event->movie->imdb);
        if ($allSubtitles->isEmpty()) {
            logger("[{$movieFullName}] None subtitle found");
            return;
        } else {
            logger("[{$movieFullName}] {$allSubtitles->count()} subtitles found");
        }
        event(new TorrentsAndSubtitlesFound($event->movie, $allTorrents, $allSubtitles));
    }

    /**
     * Retrieve all torrents that match the movie name
     *
     * @param  string $movieFullName
     * @return Collection
     */
    protected function retrieveTorrents($movieFullName)
    {
        $allTorrents = collect($this->torrentSearcher->search($movieFullName))->transform(function ($torrent) {
            $converted = [];
            foreach ((array) $torrent as $key => $value) {
                $converted[preg_match('/^\x00(?:.*?)\x00(.+)/', $key, $matches) ? $matches[1] : $key] = $value;
            }

            return $converted;
        });

        return $allTorrents;
    }

    /**
     * Retrieve all subtitles based on imdb (internet movie database) ID
     *
     * @param  int $imdbId
     * @return Collection
     */
    protected function retrieveSubtitles($imdbId)
    {
        $response = $this->subtitleSearcher->searchSubtitles([
            [
                'sublanguageid' => config('moviedownloader.opensubtitles.language'),
                'imdbid' => $imdbId,
            ],
        ]);
        $subtitles = collect($response->toArray()['data'])->where('Score', '>', 10);

        return $subtitles;
    }

}
