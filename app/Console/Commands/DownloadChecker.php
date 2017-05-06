<?php

namespace App\Console\Commands;

use App\Jobs\DownloadChecker as DownloadCheckerJob;
use Illuminate\Console\Command;

class DownloadChecker extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'download:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check the download progress of torrents at its client';

    /**
     * Create a new command instance.
     *
     * @return DownloadChecker
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @param  DownloadCheckerJob $downloadChecker
     *
     * @return mixed
     */
    public function handle(DownloadCheckerJob $downloadChecker)
    {
        while (true) {
            dispatch($downloadChecker);
            sleep(60);
        }
    }
}
