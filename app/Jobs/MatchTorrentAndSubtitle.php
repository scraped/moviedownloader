<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\{
    Movie,
    Torrent
};

class MatchTorrentAndSubtitle implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Movie
     */
    protected $movie;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Movie $movie)
    {
        $this->movie = $movie;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $torrents = $this->movie->torrents;
        $subtitles = $this->movie->subtitles;

        foreach ($torrents as $torrent) {
            /** @var Torrent $torrent */
            $maxSimilarity = 0;
            $torrentIndex = $torrent->seeders;
            $sizeIndex = ($torrent->size == 0) ? 0 : 5000 / $torrent->size;

            $selectedSubtitle = $subtitles[0];
            foreach ($subtitles as $subtitle) {
                $torrentName = preg_replace('/[^A-Za-z0-9\-]/', '', $torrent['name']);
                $subtitleName = preg_replace('/[^A-Za-z0-9\-]/', '', $subtitle['movie_release_name']);
                similar_text($torrentName, $subtitleName, $similarity);

                if ($similarity > $maxSimilarity) {
                    $maxSimilarity = $similarity;
                    $selectedSubtitle = $subtitle;
                }
            }
            $score = $maxSimilarity + $torrentIndex + $sizeIndex;

            $torrent->subtitles()->save($selectedSubtitle, [
                'score' => $score,
                'status' => 'created',
            ]);
        }
    }
}
