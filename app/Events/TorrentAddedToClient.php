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
use Transmission\Model\Torrent;

class TorrentAddedToClient
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var Torrent
     */
    public $torrent;

    /**
     * @var Movie
     */
    public $movie;

    /**
     * Create a new event instance.
     *
     * @return TorrentAddedToClient
     */
    public function __construct(Torrent $torrent, Movie $movie)
    {
        $this->torrent = $torrent;
        $this->movie = $movie;
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
