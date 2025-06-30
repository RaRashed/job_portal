<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Job;
use App\Models\Application;

class AdminController extends Controller
{
    public function metrics()
    {
        return response()->json([
            'total_users'       => User::count(),
            'total_jobs'        => Job::count(),
            'total_applications'=> Application::count(),
            'employers'         => User::where( 'role', 'employer')->count(),
            'candidates'        => User::where('role', 'candidate')->count(),
        ]);
    }
}
