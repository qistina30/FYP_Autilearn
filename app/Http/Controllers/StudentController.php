<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Student;
use App\Models\StudentProgress;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Mail\GuardianAccountDetails;
use Illuminate\Support\Facades\Mail;
use Exception;

class StudentController extends Controller
{
    // Show educator dashboard if the user is an educator
    public function index()
    {
        $students = Student::all(); // Fetch all students
        return view('student.index', compact('students'));
    }


    // Show add student form
    public function create()
    {
        return view('educator.add-students');
    }


    public function store(Request $request)
    {
        try {
            // Validate the request data
            $validatedData = $request->validate([
                'full_name' => 'required|string|max:255',
                'ic_number' => 'required|string|unique:students,ic_number',
                'guardian_name' => 'required|string|max:255',
                'contact_number' => 'required|string|max:255',
                'email' => 'nullable|email',
            ]);

            // Ensure educator is authenticated
            $educator = Auth::user();
            if (!$educator) {
                return redirect()->route('educator.add-student')->with('error', 'Unauthorized action.');
            }

            // Generate the parent user ID
            $lastUser = User::where('user_id', 'like', 'PA%')->latest('id')->first();
            $lastId = $lastUser ? (int) substr($lastUser->user_id, 6) : 0;
            $user_id = 'PA' . str_pad($lastId + 1, 5, '0', STR_PAD_LEFT);

            // Define default password
            $defaultPassword = 'test12345';

            // Create the parent user
            $parentUser = User::create([
                'user_id' => $user_id,
                'name' => $validatedData['guardian_name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($defaultPassword),
                'role' => 'guardian',
            ]);

            // Send notification email to the guardian
            if ($validatedData['email']) {
                Mail::to($validatedData['email'])->send(new GuardianAccountDetails(
                    $validatedData['guardian_name'],
                    $user_id,
                    $defaultPassword
                ));
            }

            // Create the student
            $student = Student::create([
                'full_name' => $validatedData['full_name'],
                'ic_number' => $validatedData['ic_number'],
                'guardian_name' => $validatedData['guardian_name'],
                'contact_number' => $validatedData['contact_number'],
                'educator_user_id' => $educator->user_id,
                'email' => $validatedData['email'],
            ]);

            if ($student) {
                // Retrieve all activities
                $activities = Activity::all();

                // Check if there are any activities before inserting
                if ($activities->isEmpty()) {
                    return redirect()->route('educator.add-student')->with('error', 'No activities found. Please add activities first.');
                }

                // Insert student progress using Eloquent
                foreach ($activities as $activity) {
                    StudentProgress::create([
                        'student_id' => $student->id,
                        'educator_id' => null,
                        'activity_id' => $activity->id,
                    ]);
                }
            }

            return redirect()->route('educator.add-student')->with('success', 'Student and parent account created successfully. Email sent to guardian.');

        } catch (\Exception $e) {
            return redirect()->route('educator.add-student')->with('error', 'Error: ' . $e->getMessage());
        }
    }


    // Student Dashboard
    public function dashboard()
    {
        return view('student.dashboard');
    }

    public function destroy($id)
    {
        $student = Student::findOrFail($id);

        // Find and delete the guardian user
        $parentUser = User::where('name', $student->guardian_name)->first();
        if ($parentUser) {
            Log::info("Deleting guardian user: " . $parentUser->user_id);
            $parentUser->delete();
        }

        // Delete the student
        $student->delete();

        return redirect()->route('student.index')->with('success', 'Student and parent account deleted successfully.');
    }

}
