<?php

namespace App\Http\Controllers;
use App\Models\Application;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ApplicationController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        $applications = Cache::remember("user_{$userId}_applications", now()->addMinutes(5), function () use ($userId) {
            return Application::with('job')
                ->where('user_id', $userId)
                ->get();
        });
    
        return response()->json($applications);

    }

    public function apply(Request $request, $id)
    {
        $job = Job::findOrFail($id);
      if(!$job) {
            return response()->json(['message' => 'Job not found'], 404);
        }

        // Check if the user has already applied for this job
        $existingApplication = Application::where('user_id', auth()->id())
                                          ->where('job_id', $id)
                                          ->first();
        if ($existingApplication) {
            return response()->json(['message' => 'You have already applied for this job'], 409);

      }
        $application = Application::create([
            'user_id' => auth()->id(),
            'job_id' => $id,
            // 'status' => true
        ]);

        return response()->json($application, 201);
    }

    public function jobApplications($id)
    {
        $job = Job::where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        return $job->applications()->with('user','job')->get();
    }
}

