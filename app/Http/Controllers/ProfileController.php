<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('profile.edit-profile');
    }

    /**
     * Update the guardian profile.
     */
    public function update(Request $request)
    {
        $user = Auth::user(); // Get the authenticated user

        // Validate inputs
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8|confirmed',
            'children' => 'array', // Expecting child names as an array
            'children.*' => 'string|max:255', // Each child's name should be valid
        ]);

        // Store old guardian name before updating user
        $oldGuardianName = $user->name;

        // Update the users table
        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        // Update students if user is a guardian
        if ($user->role === 'guardian') {
            foreach ($request->children as $childId => $childName) {
                $student = Student::find($childId);
                if ($student) {
                    $student->full_name = $childName;
                    $student->guardian_name = $request->name;
                    $student->email = $request->email;
                    $student->save();
                }
            }
        }

        return redirect()->route('profile.edit')->with('success', 'Profile updated successfully.');
    }





}
