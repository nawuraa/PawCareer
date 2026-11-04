<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\RequisitionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', function () {
        return response()->json(['message' => 'Registration endpoint']);
    })->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::middleware('role:hrd')->get('/hrd', function () {
        Route::get('/hrd', function () {
            return response()->json(['message' => 'hrd ok']);
    });
    Route::get('/job-postings/review', [JobPostingController::class, 'indexReview'])->name('job_postings.review');
    Route::post('/job-postings/review/{requisition}', [JobPostingController::class, 'processReview'])->name('job_postings.process_review');
    Route::resource('job-postings', JobPostingController::class)->except(['create', 'store', 'show']);
    });


    Route::get('/dashboard', function () {
        return response()->json(['message' => 'dashboard ok']);
    });

    Route::middleware('role:division_manager')->group(function () {
        Route::resource('requisitions', RequisitionController::class);
    });
});
