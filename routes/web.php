<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\EducatorController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Register routes
Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);

Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');


    // Educator Dashboard
    Route::get('/educator/dashboard', function () {
        return view('educator.dashboard'); // View for educator dashboard
    })->name('educator.dashboard');

    // Add New Student Page
    Route::get('/educator/add-student', [StudentController::class, 'create'])->name('educator.add-student');
    Route::post('/educator/store-student', [StudentController::class, 'store'])->name('educator.store-student');

