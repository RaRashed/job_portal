<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Skill;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CandidateController extends Controller
{
    // List available jobs with optional filters: keyword (title/description), location
    public function listJobs(Request $request)
    {
        $query = Job::query()->where('archived', false);

        if ($request->has('keyword')) {
            $keyword = $request->input('keyword');
            $query->where(function($q) use ($keyword) {
                $q->where('title', 'ILIKE', "%$keyword%")
                  ->orWhere('description', 'ILIKE', "%$keyword%")
                  ->orWhere('salary', 'ILIKE', "%$keyword%");
            });
        }

        if ($request->has('location')) {
            $location = $request->input('location');
            $query->where('location', 'ILIKE', "%$location%");
        }

        $jobs = $query->paginate(10);

        return response()->json($jobs);
    }

    // Attach skills to candidate
    public function attachSkills(Request $request)
    {
        $request->validate([
            'skill_ids' => 'required|array',
            'skill_ids.*' => 'exists:skills,id',
        ]);

        $candidate = Auth::user();

        $candidate->skills()->syncWithoutDetaching($request->skill_ids);

        return response()->json(['message' => 'Skills attached successfully']);
    }

    // Detach skills from candidate
    public function detachSkills(Request $request)
    {
        $request->validate([
            'skill_ids' => 'required|array',
            'skill_ids.*' => 'exists:skills,id',
        ]);

        $candidate = Auth::user();

        $candidate->skills()->detach($request->skill_ids);

        return response()->json(['message' => 'Skills detached successfully']);
    }

    // List candidate skills
    public function listSkills()
    {
        $candidate = Auth::user();
        $skills = $candidate->skills()->get();

        return response()->json($skills);
    }

    // Apply to a job
    public function applyToJob(Request $request, $jobId)
    {
        $candidate = Auth::user();

        // Check if job exists
        $job = Job::findOrFail($jobId);

        // Check if candidate already applied
        if (Application::where('user_id', $candidate->id)->where('job_id', $job->id)->exists()) {
            return response()->json(['message' => 'Already applied to this job'], 409);
        }

        $request->validate([
            'cover_letter' => 'nullable|string',
        ]);

        $application = Application::create([
            'user_id' => $candidate->id,
            'job_id' => $job->id,
            'cover_letter' => $request->cover_letter,
        ]);

        return response()->json(['message' => 'Application submitted', 'application' => $application]);
    }

    // List candidate applications
    public function listApplications()
    {
        $candidate = Auth::user();

        $applications = Application::with('job')->where('user_id', $candidate->id)->get();

        return response()->json($applications);
    }
}
