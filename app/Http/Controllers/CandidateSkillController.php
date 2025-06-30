<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Skill;
use Illuminate\Http\Request;

class CandidateSkillController extends Controller
{
    public function attachSkills(Request $request, $id)
    {
        $validated = $request->validate([
            'skills' => ['required', 'array'],
            'skills.*' => ['exists:skills,id'],
        ], [
            'skills.required' => 'The skills field is required.',
            'skills.array' => 'The skills must be an array.',
            'skills.*.exists' => 'One or more of the skills are invalid.',
        ]);

        $user = User::findOrFail($id);
        $user->skills()->syncWithoutDetaching($request->skills); // Avoid duplicates

        return response()->json(['message' => 'Skills attached successfully']);
    }

    // Detach a skill
    public function detachSkill($id, $skillId)
    {
        $user = User::findOrFail($id);
        $user->skills()->detach($skillId);

        return response()->json(['message' => 'Skill detached successfully']);
    }

    // Get candidate skills
    public function getSkills($id)
    {
        $user = User::with('skills')->findOrFail($id);
        return response()->json($user->skills);
    }
}

