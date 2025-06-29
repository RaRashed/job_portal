<?php

namespace App\Http\Controllers;
use App\Models\Skill;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    public function index()
    {
        return Skill::all();
    }

    public function store(Request $request)
    {
       // dd('Store method called');
        $data = $request->validate([
            'name' => 'required|string|unique:skills'
        ]);

        return Skill::create($data);
    }

    public function update(Request $request, $id)
    {
        $skill = Skill::findOrFail($id);
        $data = $request->validate([
            'name' => 'required|string|unique:skills,name,' . $id
        ]);

        $skill->update($data);
        return $skill;
    }

    public function destroy($id)
    {
        $skill = Skill::findOrFail($id);
        $skill->delete();

        return response()->json(['message' => 'Skill deleted']);
    }
}

