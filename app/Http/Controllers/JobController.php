<?php

namespace App\Http\Controllers;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class JobController extends Controller
{
    public function index()
    {

    $jobs = Cache::remember('recent_jobs', now()->addMinutes(5), function () {
        return Job::with('skills')->where('user_id', auth()->id())->get();
    });
    return response()->json($jobs);

    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'location' => 'required|string',
            'salary' => 'required|numeric',
            'skills' => 'array' // optional skill IDs
        ]);

        $job = Job::create([
            'user_id' => auth()->id(),
            'title' => $data['title'],
            'description' => $data['description'],
            'location' => $data['location'],
            'salary' => $data['salary'],
        ]);

        if (!empty($data['skills'])) {
            $job->skills()->sync($data['skills']);
        }

        return response()->json($job, 201);
    }

    public function update(Request $request, $id)
    {
        $job = Job::where('id', $id)->where('user_id', auth()->id())->firstOrFail();

        $data = $request->validate([
            'title' => 'string',
            'description' => 'string',
            'location' => 'string',
            'salary' => 'numeric',
            'skills' => 'array'
        ]);

        $job->update($data);

        if (isset($data['skills'])) {
            $job->skills()->sync($data['skills']);
        }

        return response()->json($job);
    }

    public function destroy($id)
    {
        $job = Job::where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        $job->delete();

        return response()->json(['message' => 'Job deleted']);
    }
}

