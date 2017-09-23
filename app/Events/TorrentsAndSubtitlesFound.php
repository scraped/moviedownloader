<?php

namespace App\Events;

use App\Movie;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class TorrentsAndSubtitlesFound
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var Movie
     */
    public $movie;

    /**
     * @var Collection
     */
    public $torrents;

    /**
     * @var Collection
     */
    public $subtitles;

    /**
     * Create a new event instance.
     *
     * @param  Movie $movie
     * @param  Collection $torrents
     * @param  Collection $subtitles
     *
     * @return TorrentsAndSubtitlesFound
     */
    public function __construct(Movie $movie, Collection $torrents, Collection $subtitles)
    {
        $this->movie = $movie;
        $this->torrents = $torrents;
        $this->subtitles = $subtitles;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
