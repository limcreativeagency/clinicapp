<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Language switching routes
Route::get('/language/{locale}', [App\Http\Controllers\LanguageController::class, 'switchLanguage'])
    ->name('language.switch')
    ->where('locale', '[a-z]{2}');

// Test email route
Route::get('/test-email', [App\Http\Controllers\TestEmailController::class, 'testEmail'])
    ->name('test.email');

// Hospital signup routes
Route::get('/signup', [App\Http\Controllers\HospitalSignupController::class, 'showSignupForm'])
    ->name('signup');
Route::post('/signup', [App\Http\Controllers\HospitalSignupController::class, 'signup'])
    ->name('signup.store');

// Access code routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/access-codes/request/{hospital}', [App\Http\Controllers\AccessCodeController::class, 'requestAccess'])
        ->name('access-codes.request');
    Route::post('/access-codes/verify', [App\Http\Controllers\AccessCodeController::class, 'verifyCode'])
        ->name('access-codes.verify');
});

// Dashboard routes
Route::middleware(['auth', 'verified'])->group(function () {
    // Super Admin Dashboard
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
    
    // Hospital Admin Dashboard
    Route::get('/hospital/dashboard', function () {
        return view('hospital.dashboard');
    })->name('hospital.dashboard');
    
    // Staff Dashboard (Doctors & Representatives)
    Route::get('/staff/patients', function () {
        return view('staff.patients');
    })->name('staff.patients');
    
    // Patient Profile
    Route::get('/patient/profile', function () {
        return view('patient.profile');
    })->name('patient.profile');
});

// Dashboard route with role-based redirect
Route::get('/dashboard', function () {
    $user = auth()->user();
    
    if ($user->isSuperAdmin()) {
        return redirect()->route('admin.dashboard');
    } elseif ($user->isAdmin()) {
        return redirect()->route('hospital.dashboard');
    } elseif ($user->isDoctor() || $user->isRepresentative()) {
        return redirect()->route('staff.patients');
    } else {
        return redirect()->route('patient.profile');
    }
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/auth.php';
