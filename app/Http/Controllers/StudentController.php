<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class StudentController extends Controller
{
    public function index()
    {
        // Ensure the user has the 'educator' role
        if (auth()->user()->hasRole('educator')) {
            return view('educator.dashboard'); // Show educator dashboard view
        } else {
            return redirect()->route('home'); // Redirect to the home route if not an educator
        }
    }

    public function create()
    {
        return view('educator.add-students');
    }

    /**
     * Store a newly created student in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'full_name' => 'required|string|max:255',
            'ic_number' => 'required|string|unique:students,ic_number',
            'age' => 'required|integer',
            'address' => 'required|string',
            'parent_name' => 'required|string|max:255',
            'contact_number' => 'required|string|max:255',
            'email' => 'nullable|email',
        ]);

        // Log validated data to ensure the request data is correct
        Log::info('Validated Data:', $validatedData);

        // Generate the parent user ID
        $lastUser = User::where('user_id', 'like', 'PA%')->latest('id')->first();
        $lastId = $lastUser ? (int) substr($lastUser->user_id, 6) : 0;
        $user_id = 'PA' . str_pad($lastId + 1, 5, '0', STR_PAD_LEFT);

        // Create the parent user (for the parent)
        $parentUser = User::create([
            'user_id' => $user_id,  // Assign the generated user ID
            'name' => $validatedData['parent_name'],
            'email' => $validatedData['email'],
            'password' => Hash::make('test123'),  // Default password for the parent
            'role' => 'parent',  // Default role as 'parent'
        ]);

        // Log parent user creation
        Log::info('Parent User Created:', ['parent' => $parentUser]);

        // Create the student and associate with the educator and the newly created parent
        $student = Student::create([
            'full_name' => $validatedData['full_name'],
            'ic_number' => $validatedData['ic_number'],
            'age' => $validatedData['age'],
            'address' => $validatedData['address'],
            'parent_name' => $validatedData['parent_name'],
            'contact_number' => $validatedData['contact_number'],
            'educator_user_id' => Auth::user()->user_id,  // Current authenticated educator
            'email' => $validatedData['email'],
        ]);

        // Log student creation
        Log::info('Student Created:', ['student' => $student]);

        // Check if both parent and student are created
        if ($parentUser && $student) {
            // Redirect with success message
            return redirect()->route('educator.add-student')->with('success', 'Student and parent account created successfully.');
        } else {
            // Redirect with error message if something goes wrong
            return redirect()->route('educator.add-student')->with('error', 'An error occurred while creating the student and parent account.');
        }
    }



    public function dashboard()
    {
        return view('student.dashboard');  // This will load the dashboard with the learning modules.
    }

    // Display Learning Module
    public function learningModule()
    {
        return view('student.learning-module.animal-recognition'); // Add the module view here.
    }


}
