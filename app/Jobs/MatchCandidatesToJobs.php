<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\Job;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class MatchCandidatesToJobs implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        $candidates = User::where('role', 'candidate')->with('skills')->get();
        $jobs = Job::with('skills')->get();

        foreach ($candidates as $candidate) {
            foreach ($jobs as $job) {
                $candidateSkillIds = $candidate->skills->pluck('id')->toArray();
                $jobSkillIds = $job->skills->pluck('id')->toArray();

                $skillsMatch = !array_diff($jobSkillIds, $candidateSkillIds);
                $locationMatch = $candidate->location == $job->location;
                $salaryMatch = $job->salary >= $candidate->expected_salary;

                if ($skillsMatch || $locationMatch || $salaryMatch) {
                    // Match found - log the notification
                    \Log::info("Matched Candidate {$candidate->name} to Job {$job->title}");
                    // Optional: Save to a matched_jobs table or send email, etc.
                }
            }
        }
    }
}
