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
     * Create a new message instance.
     *
     * @param  Torrent $torrent
     * @param  Movie $movie
     *
     * @return TorrentDownloadFinished
     */
    public function __construct(Torrent $torrent, Movie $movie)
    {
        $this->torrent = $torrent;
        $this->movie = $movie;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $movieFullName = "{$this->movie->name} {$this->movie->year}";
        return $this->to('gustavobgama@gmail.com')
            ->subject('Tem filme novo !!')
            ->view('emails.movie.downloaded')
            ->with([
                'movie' => $movieFullName,
            ]);
    }
}
