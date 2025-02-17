<?php

namespace App\Http\Controllers;


use App\Models\LearningActivity;
use App\Models\LearningMaterial;
use Database\Seeders\LearningActivitySeeder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LearningModuleController extends Controller
{
    public function index()
    {
        // Fetch all learning materials from the database
        $materials = LearningMaterial::all();

        // Return the view and pass the materials
        return view('learning.index', compact('materials'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:video,picture',
            'file' => 'required|file|mimes:jpg,png,mp4,mov,avi|max:20480',
            'audio' => 'nullable|file|mimes:mp3,wav|max:10240',
        ]);

        $filePath = $request->file('file')->store('learning_materials', 'public');
        $audioPath = $request->file('audio') ? $request->file('audio')->store('audio', 'public') : null;

        LearningMaterial::create([
            'title' => $validated['title'],
            'type' => $validated['type'],
            'file_path' => $filePath,
            'audio_path' => $audioPath,
        ]);

        return redirect()->back()->with('success', 'Learning material uploaded successfully.');
    }

    public function destroy($id)
    {
        $material = LearningMaterial::findOrFail($id);
        $material->delete();

        return redirect()->route('learning.index')->with('success', 'Material deleted successfully');
    }

    public function create()
    {
        return view('learning.create');
    }
}
