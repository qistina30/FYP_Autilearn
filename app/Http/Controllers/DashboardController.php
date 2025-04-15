<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\StudentProgress;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $role = $user->role;

        if ($role === 'admin') {
            $totalCompleted = StudentProgress::where('time_taken', '>', 0)->count();
            $totalNotAttempted = StudentProgress::where('time_taken', 0)->count();

// Total Educators
            $totalEducators = User::where('role', 'educator')->count();

// Total Guardians & Students (assuming each student is tied to a guardian)
            $totalGuardians = Student::count();

// Total Attempts
            $totalAttempts = StudentProgress::count();

// Most Active Student
            $mostActiveStudent = StudentProgress::select('student_id')
                ->selectRaw('COUNT(*) as total_attempts')
                ->groupBy('student_id')
                ->orderByDesc('total_attempts')
                ->first();

            $mostActiveStudentName = $mostActiveStudent
                ? Student::find($mostActiveStudent->student_id)?->full_name
                : 'N/A';

            $mostActiveStudentAttempts = $mostActiveStudent->total_attempts ?? 0;

// Overall average score
            $overallAverageScore = round(StudentProgress::avg('score'), 1);

// Fastest time
            $fastestTime = StudentProgress::where('time_taken', '>', 0)
                ->orderBy('time_taken', 'asc')
                ->first();

// Most active educator (based on student progress assisted)
            $mostActiveEducatorData = StudentProgress::where('time_taken', '>', 0)
                ->select('educator_id')
                ->selectRaw('COUNT(*) as total_attempts')
                ->groupBy('educator_id')
                ->orderByDesc('total_attempts')
                ->first();

            $mostActiveEducatorName = 'N/A';


            if ($mostActiveEducatorData && $mostActiveEducatorData->educator_id) {
                $educator = User::where('user_id', $mostActiveEducatorData->educator_id)
                    ->where('role', 'educator')
                    ->first();

                if ($educator) {
                    $mostActiveEducatorName = $educator->name;

                }

                // Fetch student progress data for the line chart (Completion over time)
                $progressData = StudentProgress::selectRaw('
            DATE_FORMAT(created_at, "%Y-%m-%d") as date,
            COUNT(CASE WHEN score >= 5 AND time_taken > 0 THEN 1 END) as completed
        ')
                    ->groupBy('date')
                    ->orderBy('date')
                    ->get();

                // Extract the dates and completed counts
                $dates = $progressData->pluck('date');
                $completed = $progressData->pluck('completed');
// Pass to view
            return view('dashboard', compact(
                'totalEducators',
                'totalGuardians',
                'totalAttempts',
                'mostActiveStudentName',
                'mostActiveStudentAttempts',
                'overallAverageScore',
                'fastestTime',
                'mostActiveEducatorName','role','totalCompleted', 'totalNotAttempted','dates','completed'
            ));

        }}

        if ($role === 'guardian') {
            // Get all children (students) related to this guardian
            $children = Student::with(['progress' => function ($query) {
                $query->latest()->take(5);
            }, 'progress.educator'])->where('guardian_name', $user->name)->get();

            // Prepare data for each child
            $childrenData = $children->map(function ($child) {
                $progress = $child->progress;

                // Sort from oldest to newest for chart
                $recentScores = $progress->sortBy('created_at')->pluck('score')->values();

                $latestProgress = $progress->first();

                $score = $latestProgress?->score ?? null;
                $time = $latestProgress?->time_taken ?? null;
                $attempt = $latestProgress?->attempt_number ?? null;
                $lastAttempt = $latestProgress?->created_at?->format('d M Y, h:i A') ?? '-';
                $percentage = $score ? number_format(($score / 40) * 100, 1) : null;
                $educatorName = $latestProgress?->educator?->name ?? 'Not Specified';

                // Calculate performance trend
                $trend = 'Not Enough Data';
                if ($recentScores->count() >= 2) {
                    $first = $recentScores->first();
                    $last = $recentScores->last();
                    if ($last > $first) {
                        $trend = 'Improving';
                    } elseif ($last < $first) {
                        $trend = 'Declining';
                    } else {
                        $trend = 'Consistent';
                    }
                }

                return [
                    'id' => $child->id,
                    'full_name' => $child->full_name,
                    'score' => $score,
                    'time_taken' => $time,
                    'attempt_number' => $attempt,
                    'last_attempt' => $lastAttempt,
                    'percentage' => $percentage,
                    'recent_scores' => $recentScores,
                    'trend' => $trend,
                    'assisted_by' => $educatorName,

                ];
            });

            return view('dashboard', [
                'role' => $role,
                'childrenData' => $childrenData,
            ]);
        }


        // Default return for any other role (or unauthorized access)
        return view('dashboard', compact('role'));
    }


}
