<?php

namespace App\Console\Commands;

use App\Jobs\ReadMovieSource;
use Illuminate\Console\Command;

class MovieDownloader extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'movie:download';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Download of movies and subtitles';

    /**
     * Create a new command instance.
     *
     * @return MovieDownloader
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @param  ReadMovieSource $sourceReader
     *
     * @return void
     */
    public function handle(ReadMovieSource $sourceReader)
    {
        dispatch($sourceReader);
    }
}
