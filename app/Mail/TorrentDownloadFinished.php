<?php

namespace App\Mail;

use App\Movie;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Transmission\Model\Torrent;

class TorrentDownloadFinished extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * @var Movie
     */
    protected $movie;

    /**
     * @var array
     */
    protected $receipts;

    /**
     * @var bool
     */
    protected $isSubtitleFound;

    /**
     * Create a new message instance.
     *
     * @param  Movie $movie
     * @param  array $receipts
     * @param  bool $isSubtitleFound
     *
     * @return TorrentDownloadFinished
     */
    public function __construct(Movie $movie, array $receipts, $isSubtitleFound)
    {
        $this->movie = $movie;
        $this->receipts = $receipts;
        $this->isSubtitleFound = $isSubtitleFound;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $movieFullName = "{$this->movie->name} {$this->movie->year}";
        return $this->to($this->receipts)
            ->subject('Tem filme novo !!')
            ->view('emails.movie.downloaded')
            ->with([
                'movie' => $movieFullName,
                'isSubtitleFound' => $this->isSubtitleFound,
            ]);
    }
}
