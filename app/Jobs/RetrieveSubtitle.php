<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use MovieDownloader\SubtitleRetriever;
use App\Torrent;

class RetrieveSubtitle implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Torrent
     */
    protected $torrent;

    /**
     * @var array
     */
    protected $files;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Torrent $torrent, array $files)
    {
        $this->torrent = $torrent;
        $this->files = $files;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(SubtitleRetriever $subtitleRetriever)
    {
        $subtitleRetriever->save(
            $this->torrent->subtitles->first()->link,
            $this->torrent->movie->name,
            $this->files
        );
    }
}
