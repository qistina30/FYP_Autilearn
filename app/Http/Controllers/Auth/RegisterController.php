<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;


class RegisterController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // Validate the request
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Get the last user ID starting with 'ED' and extract the numeric part
        $lastUser = User::where('user_id', 'like', 'ED%')->latest('id')->first();
        $lastId = $lastUser ? (int) substr($lastUser->user_id, 2) : 0;

        // Generate the new user ID with leading zeros
        $user_id = 'ED' . str_pad($lastId + 1, 5, '0', STR_PAD_LEFT);

        // Create a new user (but do NOT log them in)
        User::create([
            'user_id' => $user_id,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'educator',
        ]);

        // Redirect to the login page with a success message
        return redirect('/login')->with('status', 'Registration successful. Please login.');
    }
}
