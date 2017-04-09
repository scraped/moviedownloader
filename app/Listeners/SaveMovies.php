<?php

namespace App\Listeners;

use App\Events\MovieRetrieved;
use App\Events\MovieSourceRead;
use App\Movie;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SaveMovies implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return SaveMovies
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  MovieSourceRead  $event
     *
     * @return void
     */
    public function handle(MovieSourceRead $event)
    {
        foreach ($event->movies as $movie) {
            /** @var Movie $movie */
            $movie = Movie::firstOrCreate(
                [
                    'name' => $movie['name'],
                    'year' => $movie['year'],
                    'imdb' => $movie['imdb'],
                ],
                [
                    'status' => 'searching',
                ]
            );
            if (!$movie->wasRecentlyCreated) {
                continue;
            }
            $movieFullName = "{$movie->name} {$movie->year}";
            logger("[{$movieFullName}] Found new");
            event(new MovieRetrieved($movie));
        }
    }
}
