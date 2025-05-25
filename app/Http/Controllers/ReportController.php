<?php

namespace App\Http\Controllers;

use App\Models\Student;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Models\StudentProgress;

class ReportController extends Controller
{
    public function overallPerformance()
    {
        // Get all student progress data
        $progress = StudentProgress::all();
        $totalAttempts = $progress->count();

        // Average score across all attempts
        $averageScore = round($progress->avg('score'), 2);

        // Best score per student with student name
        $bestScoresFull = $progress->groupBy('student_id')->map(function ($group) {
            return [
                'student_id' => $group->first()->student_id,
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

        // ✅ Most Active Student
        $mostActiveStudentData = $progress->groupBy('student_id')
            ->map(function ($group) {
                return [
                    'student_id' => $group->first()->student_id,
                    'student_name' => $group->first()->student->full_name ?? 'N/A',
                    'attempts' => $group->count()
                ];
            })->sortByDesc('attempts')->first();

        $mostActiveStudentName = $mostActiveStudentData['student_name'] ?? 'N/A';
        $mostActiveStudentAttempts = $mostActiveStudentData['attempts'] ?? 0;

        // ✅ Fastest Time Taken (excluding empty or null)
        $fastestTime = $progress->where('time_taken', '>', 0)->sortBy('time_taken')->first();

        // ✅ Overall average score (can reuse averageScore or keep separately if needed)
        $overallAverageScore = $averageScore;

        // Pass all data to the view
        return view('report.analytics', [
            'averageScore' => $averageScore,
            'bestScoresTop5' => $bestScoresTop5,
            'bestScoresFull' => $bestScoresFull,
            'averageTime' => $averageTime,
            'completedCount' => $completedCount,
            'totalAttempts' => $totalAttempts,
            'averageAttemptsPerStudent' => $averageAttemptsPerStudent,

            // New values
            'mostActiveStudentName' => $mostActiveStudentName,
            'mostActiveStudentAttempts' => $mostActiveStudentAttempts,
            'overallAverageScore' => $overallAverageScore,
            'fastestTime' => $fastestTime,
        ]);
    }


    public function show($id)
    {
        $student = Student::findOrFail($id);

        // Guardian can only access their own child's report
        if (auth()->user()->role === 'guardian' && $student->guardian_name !== auth()->user()->name) {
            abort(403, 'Unauthorized access to this report.');
        }

        $progress = StudentProgress::where('student_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();

        $averageScore = $progress->avg('score');
        $averageTime = $progress->avg('time_taken');
        $attempts = $progress->max('attempt_number');
        $last7DaysAttempts = $progress->where('created_at', '>=', now()->subDays(7))->count();

        return view('report.show', [
            'student' => $student,
            'progress' => $progress,
            'averageScore' => $averageScore,
            'averageTime' => $averageTime,
            'attempts' => $attempts,
            'last7DaysAttempts' => $last7DaysAttempts,
        ]);
    }


    public function storeNotes(Request $request, $id)
    {
        $request->validate([
            'educator_notes' => 'nullable|string|max:1000',
        ]);

        $progress = StudentProgress::findOrFail($id);
        $progress->educator_notes = $request->educator_notes;
        $progress->save();

        return redirect()->back()->with('success', 'Notes updated successfully!');
    }



    public function downloadPdf($studentId)
    {
        $student = Student::findOrFail($studentId);

        $progress = StudentProgress::where('student_id', $student->id)->with('educator')->get();
        $averageScore = $progress->avg('score');
        $averageTime = $progress->avg('time_taken');
        $attempts = $progress->count();

        $pdf = Pdf::loadView('report.pdf', compact('student', 'progress', 'averageScore', 'averageTime', 'attempts'));
        return $pdf->download('report_' . $student->full_name . '.pdf');
    }
}
