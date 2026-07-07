<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class ResetPeerOptIn extends Command
{
    protected $signature = 'app:reset-peer-opt-in';
    protected $description = 'Reset is_opted_in to false for all users who were auto-opted-in without explicit consent';

    public function handle(): int
    {
        $count = User::where('is_opted_in', true)->count();

        if ($count === 0) {
            $this->info('No users currently have is_opted_in = true. Nothing to reset.');
            return Command::SUCCESS;
        }

        if (!$this->confirm("This will reset is_opted_in to false for {$count} user(s). They will need to opt in again via Profile settings. Continue?")) {
            $this->info('Cancelled.');
            return Command::SUCCESS;
        }

        User::where('is_opted_in', true)->update(['is_opted_in' => false]);

        $this->info("Reset is_opted_in to false for {$count} user(s).");
        $this->warn('These users were never given an explicit opt-in choice under the previous onboarding flow.');
        $this->info('They can re-enable peer network visibility from their Profile settings.');

        return Command::SUCCESS;
    }
}
