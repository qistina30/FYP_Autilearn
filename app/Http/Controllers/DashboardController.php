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

        // If the user is a guardian
        if ($role === 'guardian') {
            // Fetch children data for guardians
            $children = $user->student ?? []; // Assuming a relationship exists
            return view('dashboard', compact('role', 'children'));
        }

        // Default return for any other role (or unauthorized access)
        return view('dashboard', compact('role'));
    }

// Example function to calculate average score
   /* private function getAverageScore()
    {
        $educatorScores = User::where('role', 'educator')->pluck('score'); // Assuming educators have a score attribute
        return $educatorScores->avg();
    }

// Example function to calculate average time spent by guardians
    private function getAverageTime()
    {
        $guardianActivities = Activity::whereHas('user', function ($query) {
            $query->where('role', 'guardian');
        })->pluck('duration'); // Assuming activities have a 'duration' attribute
        return $guardianActivities->avg();
    }

// Example function to get the most active user
    private function getMostActiveUsers()
    {
        $activeUsers = User::withCount('activities')->orderBy('activities_count', 'desc')->take(1)->first();
        return $activeUsers ? $activeUsers->name : 'N/A';
    }*/

}
