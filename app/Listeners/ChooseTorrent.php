<?php

namespace App\Listeners;

use App\Events\TorrentChosen;
use App\Events\TorrentsAndSubtitlesFound;
use App\Movie;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;

class ChooseTorrent implements ShouldQueue
{

    /**
     * @var Movie
     */
    protected $movie;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  TorrentsAndSubtitlesFound  $event
     * @return void
     */
    public function handle(TorrentsAndSubtitlesFound $event)
    {
        $this->movie = $event->movie;
        $movieFullName = "{$this->movie->name} {$this->movie->year}";
        $torrents = $this->calculateSimilarityBetweenTorrentAndSubtitle($event->torrents, $event->subtitles);
        $torrentFiltered = $torrents->sortByDesc('similarity')->sortByDesc('seeders')->first();
        $this->movie->subtitle = $torrentFiltered['SubDownloadLink'];
        $this->movie->save();
        event(new TorrentChosen($this->movie, $torrentFiltered));
    }

    /**
     * Append to torrent the max similarity with subtitle and the link to download it
     *
     * @param  Collection $torrents
     * @param  Collection $subtitles
     * @return Collection
     */
    protected function calculateSimilarityBetweenTorrentAndSubtitle($torrents, $subtitles)
    {
        return $torrents->transform(function ($torrent) use ($subtitles) {
            $maxSimilarity = 0;
            foreach ($subtitles as $subtitle) {
                $torrentName = preg_replace('/[^A-Za-z0-9\-]/', '', $torrent['name']);
                $subtitleName = preg_replace('/[^A-Za-z0-9\-]/', '', $subtitle['MovieReleaseName']);
                similar_text($torrentName, $subtitleName, $similarity);
                if ($similarity > $maxSimilarity) {
                    $maxSimilarity = $similarity;
                    $torrent['similarity'] = $maxSimilarity;
                    $torrent['SubDownloadLink'] = $subtitle['SubDownloadLink'];
                }
            }

            return $torrent;
        });
    }

}
