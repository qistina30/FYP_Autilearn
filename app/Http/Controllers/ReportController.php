<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use App\Models\StudentProgress;

class ReportController extends Controller
{
    public function overallPerformance()
    {
        // Get all student progress data (you can filter by status if needed)
        $progress = StudentProgress::all();
        $totalAttempts = $progress->count();

        // Average score across all attempts
        $averageScore = round($progress->avg('score'), 2);

        // Best score per student with student name
        $bestScoresFull = $progress->groupBy('student_id')->map(function ($group) {
            return [
                'student_name' => $group->first()->student->full_name ?? 'N/A',
                'best_score' => $group->max('score'),
            ];
        })->sortByDesc('best_score')->values();

        $bestScoresTop5 = $bestScoresFull->take(5);


        // Average time taken
        $averageTime = round($progress->avg('time_taken'), 2);

        // Count of unique completed submissions
        $completedCount = $progress->where('status', 'completed')->count();

        // Calculate average attempts per student
        $uniqueStudents = $progress->groupBy('student_id')->count(); // Count unique students
        $averageAttemptsPerStudent = $totalAttempts / $uniqueStudents;

        // Pass all the data to the view
        return view('report.analytics', [
            'averageScore' => $averageScore,
            'bestScoresTop5' => $bestScoresTop5,
            'bestScoresFull' => $bestScoresFull,
            'averageTime' => $averageTime,
            'completedCount' => $completedCount,
            'totalAttempts' => $totalAttempts,
            'averageAttemptsPerStudent' => $averageAttemptsPerStudent,
        ]);

    }

    public function show($id)
    {
        $student = Student::findOrFail($id); // assuming there's a Student model
        $progress = StudentProgress::where('student_id', $id)
            ->join('students', 'student_progress.student_id', '=', 'students.id')  // Join with students table
            ->orderBy('activity_id')
            ->orderBy('attempt_number')
            ->select('student_progress.*', 'students.name as student_name') // Select student name along with other fields
            ->get();

        return view('report.show', compact('student', 'progress'));
    }


}
