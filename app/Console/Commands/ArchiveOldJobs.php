<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Job;
use Carbon\Carbon;

class ArchiveOldJobs extends Command
{
    protected $signature = 'jobs:archive-old';
    protected $description = 'Archive job posts older than 30 days';

    public function handle()
    {
        $count = Job::where('created_at', '<', Carbon::now()->subDays(30))
                    ->where('archived', false)
                    ->update(['archived' => true]);

        $this->info("Archived $count job(s).");
    }
}
