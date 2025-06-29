<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Skill;
use Illuminate\Http\Request;

class JobSkillController extends Controller
{
    // Attach skill(s) to a job
    public function attachSkills(Request $request, $jobId)
    {
        $request->validate([
            'skills' => 'required|array',
            'skills.*' => 'exists:skills,id'
        ]);

        $job = Job::findOrFail($jobId);
        $job->skills()->syncWithoutDetaching($request->skills);

        return response()->json(['message' => 'Skills attached to job successfully']);
    }

    // Detach a skill
    public function detachSkill($jobId, $skillId)
    {
        $job = Job::findOrFail($jobId);
        $job->skills()->detach($skillId);

        return response()->json(['message' => 'Skill detached from job successfully']);
    }

    // Get job skills
    public function getSkills($jobId)
    {
        $job = Job::with('skills')->findOrFail($jobId);
        return response()->json($job->skills);
    }
}

