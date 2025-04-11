<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\EducatorController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LearningModuleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');


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
//Route::get('/{student}/edit', [StudentController::class, 'edit'])->name('student.edit'); // Edit student form
//Route::put('/{student}', [StudentController::class, 'update'])->name('student.update'); // Update student
Route::delete('/{student}', [StudentController::class, 'destroy'])->name('student.destroy'); // Delete student

Route::post('/activity/store-progress', [ActivityController::class, 'storeProgress'])->name('activity.store-progress');
Route::get('/activity/start', [ActivityController::class, 'start'])->name('activity.start');

Route::get('/educators', [EducatorController::class, 'index'])->name('educator.index');

Route::middleware(['auth'])->group(function () {
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
});

Route::get('/activity/video', [ActivityController::class, 'video'])->name('activity.video');
Route::get('/activity/welcome', [ActivityController::class, 'welcome'])->name('activity.welcome');

Route::get('/lang/{locale}', function (\Illuminate\Http\Request $request, $locale) {
    // Validate that the locale is one of the supported languages
    $supportedLocales = ['en', 'ms'];  // Add all supported locales here
    if (in_array($locale, $supportedLocales)) {
        session(['app_locale' => $locale]);  // Store the locale in the session
        app()->setLocale($locale);  // Set the locale in the app instance
    }

    return redirect()->back();  // Redirect back to the previous page
})->name('set.language');

Route::get('/report/overall-performance', [ReportController::class, 'overallPerformance'])->name('report.analytics');
//Route::get('/report/{id}', [ReportController::class, 'show'])->name('report.show');
