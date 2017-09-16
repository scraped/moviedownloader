<?php

namespace App\Listeners;

use App\Events\TorrentChosen;
use App\Events\TorrentsFound;
use App\Movie;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Kminek\OpenSubtitles\Client;

class FilterTorrents implements ShouldQueue
{

    /**
     * @var Movie
     */
    protected $movie;

    /**
     * @var Client
     */
    protected $subtitleSearcher;

    /**
     * Create the event listener.
     *
     * @param Client $subtitleSearcher
     *
     * @return FilterTorrents
     */
    public function __construct(Client $subtitleSearcher)
    {
        $this->subtitleSearcher = $subtitleSearcher;
    }

    /**
     * Handle the event.
     *
     * @param  TorrentsFound  $event
     *
     * @return void
     */
    public function handle(TorrentsFound $event)
    {
        $this->movie = $event->movie;
        $movieFullName = "{$this->movie->name} {$this->movie->year}";
        $allTorrents = $event->torrents;
        $quantityOfTorrents = $allTorrents->count();
        logger("[{$movieFullName}] {$quantityOfTorrents} torrents found");
        $torrentFiltered = $this->getMostSeededTorrentThatHasSubtitle($allTorrents);
        if (empty($torrentFiltered)) {
            logger("[{$movieFullName}] After filtering no torrent left.");
            return;
        }
        event(new TorrentChosen($this->movie, $torrentFiltered));
    }

    /**
     * Filter torrents based on its available subtitles
     *
     * @param  Collection $allTorrents
     *
     * @return array
     */
    protected function getMostSeededTorrentThatHasSubtitle($allTorrents)
    {
        $response = $this->subtitleSearcher->searchSubtitles([
            [
                'sublanguageid' => config('moviedownloader.opensubtitles.language'),
                'imdbid' => $this->movie->imdb,
            ],
        ]);
        $subtitles = collect($response->toArray()['data']);
        $torrentsSortedBySeeders = $allTorrents->where('size', '<', config('moviedownloader.torrent_filters.max_size'))
            ->sortByDesc('seeders')
            ->all();
        foreach ($torrentsSortedBySeeders as $torrent) {
            $subtitle = $subtitles->where('MovieReleaseName', $torrent['name'])->first();
            if ($subtitle) {
                $this->movie->subtitle = $subtitle['SubDownloadLink'];
                $this->movie->save();
                return $torrent;
            }
            $subtitle = $subtitles->sortByDesc('SubRating')->first();
            if ($subtitle) {
                $this->movie->subtitle = $subtitle['SubDownloadLink'];
                $this->movie->save();
                return $torrent;
            }
        }
    }

}
