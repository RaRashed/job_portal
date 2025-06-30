<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\MatchCandidatesToJobs;

class RunJobMatcher extends Command
{
    protected $signature = 'jobs:match-candidates';
    protected $description = 'Dispatch the background job for matching candidates to jobs';

    public function handle()
    {
        MatchCandidatesToJobs::dispatch();
        $this->info('MatchCandidatesToJobs dispatched.');
    }
}
