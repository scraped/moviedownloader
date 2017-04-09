<?php

namespace App\Listeners;

use App\Events\TorrentChosen;
use App\Events\TorrentsFound;
use App\Jobs\SubtitleSearchers;
use App\Movie;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class FilterTorrents implements ShouldQueue
{

    /**
     * @var SubtitleSearchers
     */
    protected $subtitleSearchers;

    /**
     * @var Movie
     */
    protected $movie;

    /**
     * @var int
     */
    protected $maxSize;

    /**
     * Create the event listener.
     *
     * @param  SubtitleSearchers $subtitleSearchers
     *
     * @return FilterTorrents
     */
    public function __construct(SubtitleSearchers $subtitleSearchers)
    {
        $this->subtitleSearchers = $subtitleSearchers;
        $this->maxSize = config('moviedownloader.torrent_filters.max_size');
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
        $torrentsSortedBySeeders = $allTorrents->where('size', '<', $this->maxSize)
            ->sortByDesc('seeders')
            ->all();
        $torrentFiltered = $this->getMostSeededTorrentThatHasSubtitle($torrentsSortedBySeeders);
        if (empty($torrentFiltered)) {
            logger("[{$movieFullName}] After filtering no torrent left.");
            return;
        }
        event(new TorrentChosen($this->movie, $torrentFiltered));
    }

    /**
     * Filter torrents based on its available subtitles
     *
     * @param  array $torrents
     *
     * @return array
     */
    protected function getMostSeededTorrentThatHasSubtitle($torrents)
    {
        foreach ($torrents as $torrent) {
            $subtitles = collect($this->subtitleSearchers->getAll($this->movie->imdb, $torrent['tags']));
            $subtitle = $subtitles->where('MovieReleaseName', $torrent['title'])->first();
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
