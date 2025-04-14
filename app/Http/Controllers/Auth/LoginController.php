<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them based on their roles.
    |
    */

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected function redirectTo()
    {
        if (Auth::user()->role === 'admin') {
            return '/activity/welcome';
        } elseif (Auth::user()->role === 'guardian' || Auth::user()->role === 'admin') {
            return '/dashboard';
        }
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Show the login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('auth.login'); // Ensure your login form is located here
    }

    /**
     * Override the default username function to use 'user_id' instead of 'email'.
     */
    public function username()
    {
        return 'user_id';
    }

    /**
     * Handle login attempt.
     */
    public function login(Request $request)
    {
        $request->validate([
            'user_id' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::attempt(['user_id' => $request->user_id, 'password' => $request->password])) {
            return redirect()->intended($this->redirectTo());
        }

        return back()->withErrors(['user_id' => 'Invalid ID or password.']);
    }

    /**
     * Logout the user.
     */
    public function logout()
    {
        auth()->logout();
        return redirect('/'); // Redirect to the home page or login page
    }
}
