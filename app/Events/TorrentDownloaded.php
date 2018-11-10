<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use App\Torrent;

class TorrentDownloaded
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var Torrent
     */
    public $torrent;

    /**
     * @var array
     */
    public $files;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Torrent $torrent, array $files)
    {
        $this->torrent = $torrent;
        $this->files = $files;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
