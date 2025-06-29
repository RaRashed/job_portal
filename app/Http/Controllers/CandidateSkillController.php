<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Skill;
use Illuminate\Http\Request;

class CandidateSkillController extends Controller
{
    public function attachSkills(Request $request, $userId)
    {
        $request->validate([
            'skills' => 'required|array',
            'skills.*' => 'exists:skills,id'
        ]);

        $user = User::findOrFail($userId);
        $user->skills()->syncWithoutDetaching($request->skills); // Avoid duplicates

        return response()->json(['message' => 'Skills attached successfully']);
    }

    // Detach a skill
    public function detachSkill($userId, $skillId)
    {
        $user = User::findOrFail($userId);
        $user->skills()->detach($skillId);

        return response()->json(['message' => 'Skill detached successfully']);
    }

    // Get candidate skills
    public function getSkills($userId)
    {
        $user = User::with('skills')->findOrFail($userId);
        return response()->json($user->skills);
    }
}

