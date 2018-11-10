<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use MovieDownloader\SourceReaders\RemoteMovieProvider;
use App\{
    Movie,
    Torrent
};

class MovieSave extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'movies:save';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Read movies from source and record at database.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @param  RemoteMovieProvider $movieProvider
     * @return mixed
     */
    public function handle(RemoteMovieProvider $movieProvider)
    {
        $movies = $movieProvider->getMovies();
        foreach ($movies as $movie) {
            if (Movie::where('imdb', $movie['imdb'])->exists()) {
                continue;
            }

            Movie::create([
                'name' => $movie['name'],
                'imdb' => $movie['imdb'],
                'year' => $movie['year'],
            ]);
        }
    }
}
