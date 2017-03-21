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

class TorrentChosen
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var Movie
     */
    public $movie;

    /**
     * @var array
     */
    public $torrent;

    /**
     * Create a new event instance.
     *
     * @param  Movie $movie
     * @param  array $torrent
     *
     * @return TorrentChosen
     */
    public function __construct(Movie $movie, array $torrent)
    {
        $this->movie = $movie;
        $this->torrent = $torrent;
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
