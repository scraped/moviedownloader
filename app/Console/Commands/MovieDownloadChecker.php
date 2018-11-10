<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Transmission\Transmission;
use App\Torrent as TorrentModel;
use Transmission\Model\Torrent;
use App\Events\TorrentDownloaded;
use App\TorrentSubtitle;

class MovieDownloadChecker extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'movie:download-check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check if movie torrent download at client is finished.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @param  Transmission $client
     * @return mixed
     */
    public function handle(Transmission $client)
    {
        TorrentModel::whereNotNull('client_hash')->get()->each(function ($torrentModel) use ($client) {
            /** @var TorrentModel $torrentModel */
            try {
                /** @var Torrent $torrent */
                $torrent = $client->get($torrentModel->client_hash);
            } catch (Exception $e) {
                return true;
            }

            if (!$torrent->isFinished()) {
                return true;
            }

            $torrentModel->client_hash = null;
            $torrentModel->save();

            TorrentSubtitle::where('fk_torrent', $torrentModel->id)
                ->where('status', 'to download')
                ->first()
                ->update([
                    'status' => 'downloaded',
                ]);

            $files = array_map(function ($file) {
                return (string) $file;
            }, $torrent->getFiles());

            event(new TorrentDownloaded($torrentModel, $files));
        });
    }
}
