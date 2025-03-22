<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\EducatorController;
use App\Http\Controllers\LearningModuleController;
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

// Student Dashboard
Route::get('/student/dashboard', [StudentController::class, 'dashboard'])->name('student.dashboard');
Route::get('/student', [StudentController::class, 'index'])->name('student.index');
Route::get('/{student}/edit', [StudentController::class, 'edit'])->name('student.edit'); // Edit student form
Route::put('/{student}', [StudentController::class, 'update'])->name('student.update'); // Update student
Route::delete('/{student}', [StudentController::class, 'destroy'])->name('student.destroy'); // Delete student
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/learning', [LearningModuleController::class, 'index'])->name('learning.index');
Route::post('/learning/store', [LearningModuleController::class, 'store'])->name('learning.store');
Route::get('/learning/create', [LearningModuleController::class, 'create'])->name('learning.create');
Route::delete('/learning/{id}', [LearningModuleController::class, 'destroy'])->name('learning.destroy');


//Route::get('/activity/hard', [ActivityController::class, 'hard'])->name('activity.hard');
Route::get('/activity/choose-level', [ActivityController::class, 'chooseLevel'])->name('choose.level');
Route::post('/activity/store-progress', [ActivityController::class, 'storeProgress'])->name('activity.store-progress');
Route::get('/activity/basic', [ActivityController::class, 'basic'])->name('activity.basic');
Route::get('/activity/intermediate', [ActivityController::class, 'intermediate'])
    ->name('activity.intermediate');
Route::get('/activity/math', [ActivityController::class, 'math'])->name('activity.math');

Route::get('/educators', [EducatorController::class, 'index'])->name('educator.index');
