<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class RemoveUnverifiedUsers extends Command
{
    protected $signature = 'users:remove-unverified';
    protected $description = 'Delete users who never verified their email';

    public function handle()
    {
        $count = User::whereNull('email_verified_at')->delete();
        $this->info("Deleted $count unverified user(s).");
    }
}
