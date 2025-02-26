<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\StudentProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ActivityController extends Controller
{
    public function hard()
    {
        $students = Student::all(); // Fetch all students
        return view('activity.hard', compact('students')); // Pass students to the view
    }
    public function basic()
    {
        $students = Student::all(); // Fetch all students
        return view('activity.basic', compact('students')); // Pass students to the view
    }

    public function basicLetterTracing()
    {
        $students = Student::all();
        return view('activity.basicLetterTracing', compact('students'));
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
    public function storeProgress(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'educator_id' => 'required|exists:users,user_id',
            'score' => 'required|integer',
            'time_taken' => 'required|integer',
//            'status' => 'required|string'
        ]);

        StudentProgress::create([
            'student_id' => $request->student_id,
            'educator_id' => $request->educator_id,
            'score' => $request->score,
            'time_taken' => $request->time_taken,
//            'status' => $request->status
        ]);

        return response()->json(['message' => 'Progress saved successfully']);
    }

}
