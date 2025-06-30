<?php

use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\CandidateSkillController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\JobSkillController;
use App\Http\Controllers\SkillController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::middleware('auth:api')->get('me', [AuthController::class, 'me']);
Route::middleware('auth:api')->post('logout', [AuthController::class, 'logout']);


// Candidate Skills
Route::post('/candidates/{id}/skills', [CandidateSkillController::class, 'attachSkills']);
Route::delete('/candidates/{id}/skills/{skillId}', [CandidateSkillController::class, 'detachSkill']);
Route::get('/candidates/{id}/skills', [CandidateSkillController::class, 'getSkills']);

// Job Skills
Route::post('/jobs/{id}/skills', [JobSkillController::class, 'attachSkills']);
Route::delete('/jobs/{id}/skills/{skillId}', [JobSkillController::class, 'detachSkill']);
Route::get('/jobs/{id}/skills', [JobSkillController::class, 'getSkills']);



// Route::middleware('auth:api')->group(function () {
//     Route::get('jobs-list', [JobController::class, 'index']);
//     Route::post('jobs', [JobController::class, 'store']);
//     Route::put('jobs/{id}', [JobController::class, 'update']);
//     Route::delete('jobs/{id}', [JobController::class, 'destroy']);
// });
Route::middleware(['auth:api'])->group(function () {
    Route::get('jobs-list', [JobController::class, 'index']);
    Route::middleware(['role:employer'])->group(function () {
        Route::post('jobs', [JobController::class, 'store']);
        Route::put('jobs/{id}', [JobController::class, 'update']);
        Route::delete('jobs/destroy/{id}', [JobController::class, 'destroy']);

        Route::get('jobs/{id}/applications', [ApplicationController::class, 'jobApplications']);
    });
});
Route::middleware('auth:api')->group(callback: function () {
    Route::middleware(['role:candidate'])->group(function () {
    Route::get('candidates/list-jobs', [CandidateController::class, 'listJobs']);
    Route::get('applications', [ApplicationController::class, 'index']); // for candidate
    Route::post('jobs/apply/{id}', [ApplicationController::class, 'apply']);


    Route::post('candidates', [CandidateController::class, 'store']);
    Route::put('candidates/{id}', [CandidateController::class, 'update']);
    Route::delete('candidates/{id}', [CandidateController::class, 'destroy']);
});
});
Route::middleware('auth:api')->group(function () {
    Route::get('skills-list', [SkillController::class, 'index']);
    Route::post('skills', [SkillController::class, 'store']);
    Route::put('skills/{id}', [SkillController::class, 'update']);
    Route::delete('skills/{id}', [SkillController::class, 'destroy']);
});

Route::middleware(['auth:api', 'role:admin'])->group(function () {
    Route::get('admin/metrics', [AdminController::class, 'metrics']);
});

