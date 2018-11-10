<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Movie;
use Kminek\OpenSubtitles\Client;

class SubtitleSearch implements ShouldQueue
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
     * @param  Client $searcher
     * @return void
     */
    public function handle(Client $searcher)
    {
        $response = $searcher->searchSubtitles([
            [
                'sublanguageid' => config('moviedownloader.opensubtitles.language'),
                'imdbid' => $this->movie->imdb,
            ],
        ]);
        $subtitles = $response->toArray()['data'];

        foreach ($subtitles as $subtitle) {
            $this->movie->subtitles()->create([
                'movie_release_name' => $subtitle['MovieReleaseName'],
                'rating' => $subtitle['SubRating'],
                'votes' => $subtitle['SubSumVotes'],
                'downloads' => $subtitle['SubDownloadsCnt'],
                'link' => $subtitle['SubDownloadLink'],
            ]);
        }
    }
}
