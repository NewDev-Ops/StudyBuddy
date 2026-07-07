<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class InspectSubjectSuggester extends Command
{
    protected $signature = 'app:inspect-subject-suggester {user? : The user ID or email. If omitted, runs for the first user.}';
    protected $description = 'Debug the subject suggester logic for a given user.';

    public function handle()
    {
        $identifier = $this->argument('user');

        if ($identifier) {
            $user = User::where('id', $identifier)
                ->orWhere('email', $identifier)
                ->first();
        } else {
            $user = User::first();
        }

        if (!$user) {
            $this->error('User not found.');
            return 1;
        }

        $this->line("User: {$user->name} ({$user->email})");
        $this->line("Subjects:");

        $subjects = $user->subjects()->with('revisionSessions')->get();

        if ($subjects->isEmpty()) {
            $this->warn('  No subjects found for this user.');
        }

        foreach ($subjects as $subject) {
            $lastSession = $subject->revisionSessions()->latest('date')->first();
            $sessionCount = $subject->revisionSessions()->count();
            $lastDate = $lastSession?->date?->toDateString() ?? 'NEVER';
            $daysAgo = $lastSession ? $lastSession->date->diffInDays(now()) : '—';
            $this->line("  [{$subject->id}] {$subject->name} — sessions: {$sessionCount}, last: {$lastDate} ({$daysAgo} days ago)");
        }

        $this->newLine();
        $this->line("Suggested subject:");

        $suggested = $user->suggestedSubject();

        if ($suggested) {
            $lastStudied = $suggested->last_studied_date;
            $reason = $lastStudied
                ? 'Last studied ' . \Carbon\Carbon::parse($lastStudied)->diffForHumans()
                : 'Never studied';
            $this->info("  → {$suggested->name} ({$reason})");
        } else {
            $this->warn('  No suggestion (user has no subjects).');
        }

        return 0;
    }
}
