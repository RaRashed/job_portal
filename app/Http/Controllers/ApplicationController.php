<?php

namespace App\Http\Controllers;
use App\Models\Application;
use App\Models\Job;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    public function index()
    {
        return Application::with('job')->where('user_id', auth()->id())->get();
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

