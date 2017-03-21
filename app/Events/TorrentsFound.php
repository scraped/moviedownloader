<?php

namespace App\Events;

use App\Movie;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Support\Collection;

class TorrentsFound
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
     * Create a new event instance.
     *
     * @param  Movie $movie
     * @param  Collection $torrents
     *
     * @return TorrentsFound
     */
    public function __construct(Movie $movie, Collection $torrents)
    {
        $this->movie = $movie;
        $this->torrents = $torrents;
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
