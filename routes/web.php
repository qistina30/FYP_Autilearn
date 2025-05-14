<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
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

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');


    // Add New Student Page
    Route::get('/educator/add-student', [StudentController::class, 'create'])->name('educator.add-student');
    Route::post('/educator/store-student', [StudentController::class, 'store'])->name('educator.store-student');
Route::get('/educator/{educator}/edit', [EducatorController::class, 'edit'])->name('educator.edit');
Route::delete('/educator/{id}', [EducatorController::class, 'destroy'])->name('educator.destroy');
Route::put('/educator/{id}', [EducatorController::class, 'update'])->name('educator.update');



// Student Dashboard
Route::get('/student/dashboard', [StudentController::class, 'dashboard'])->name('student.dashboard');
Route::get('/student', [StudentController::class, 'index'])->name('student.index');
Route::get('/students/{id}/edit', [StudentController::class, 'edit'])->name('student.edit');
Route::put('/students/{id}', [StudentController::class, 'update'])->name('student.update');
Route::delete('/{student}', [StudentController::class, 'destroy'])->name('student.destroy'); // Delete student
Route::get('/students/search', [StudentController::class, 'search'])->name('student.search');
Route::get('/student/{id}', [StudentController::class, 'show'])->name('student.show');

Route::post('/activity/store-progress', [ActivityController::class, 'storeProgress'])->name('activity.store-progress');
Route::get('/activity/start', [ActivityController::class, 'start'])->name('activity.start');

Route::get('/educators', [EducatorController::class, 'index'])->name('educator.index');

Route::middleware(['auth'])->group(function () {
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
});


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
Route::get('/report/{id}', [ReportController::class, 'show'])->name('report.show');
Route::put('/educator-notes/{id}', [ReportController::class, 'storeNotes'])->name('educator.notes.store');

Route::get('/admin/users', [AdminController::class, 'index'])->name('admin.index');
// Route for deleting a user
Route::delete('/admin/{id}', [AdminController::class, 'destroy'])->name('admin.destroy');

Route::get('/admin/search', [AdminController::class, 'search'])->name('admin.search');
Route::get('/admin/{id}/edit', [AdminController::class, 'edit'])->name('admin.edit');
Route::put('/admin/{id}', [AdminController::class, 'update'])->name('admin.update');
