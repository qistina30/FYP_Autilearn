<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\StudentProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ActivityController extends Controller
{
    public function math()
    {
        $students = Student::all(); // Assuming you have a Student model

        return view('activity.math', compact('students'));
    }
    public function basic()
    {
        $students = Student::all(); // Fetch all students
        return view('activity.basic', compact('students')); // Pass students to the view
    }


    public function intermediate()
    {
        $students = Student::all(); // Fetch all students
        return view('activity.intermediate', compact('students')); // Pass students to the view
    }
    // Show the level selection page
    public function chooseLevel()
    {
        return view('activity.chooseLevel');
    }

    // Store student progress
    public function storeProgress(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|integer',
            'educator_id' => 'required|integer',
            'activity_id' => 'required|integer',
            'score' => 'required|integer',
            'time_taken' => 'required|integer',
        ]);

        $validated['educator_id'] = Auth::user()->user_id;

        $progress = StudentProgress::where('student_id', $validated['student_id'])
            ->where('activity_id', $validated['activity_id'])
            ->latest('created_at')
            ->first();

        if ($progress && $progress->attempt_number == 0) {
            // If there's an existing record with attempt = 0, update it
            $progress->update([
                'score' => $validated['score'],
                'time_taken' => $validated['time_taken'],
                'educator_id' => $validated['educator_id'],
                 'attempt_number' => 1
            ]);
        } else {
            // Otherwise, create a new record with a new attempt count
            $validated['attempt_number'] = ($progress ? $progress->attempt_number + 1 : 0);
            StudentProgress::create($validated);
        }

        return response()->json(['message' => 'Progress saved successfully'], 200);
    }




}
