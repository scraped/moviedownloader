<?php

namespace App\Mail;

use App\Movie;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Transmission\Model\Torrent;

class TorrentDownloadFinished extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * @var Torrent
     */
    protected $torrent;

    /**
     * @var Movie
     */
    protected $movie;

    /**
     * @var array
     */
    protected $receipts;

    /**
     * Create a new message instance.
     *
     * @param  Torrent $torrent
     * @param  Movie $movie
     * @param  array $receipts
     *
     * @return TorrentDownloadFinished
     */
    public function __construct(Torrent $torrent, Movie $movie, array $receipts)
    {
        $this->torrent = $torrent;
        $this->movie = $movie;
        $this->receipts = $receipts;
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
            ]);
    }
}
