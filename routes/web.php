<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ApplicantJobPostingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TrashController;
use App\Http\Controllers\ApplicantProfileController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\ApplicationScreeningController;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\InterviewController;
use App\Http\Controllers\HiringDecisionController;
use App\Http\Controllers\JobPostingController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\RequisitionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function (Request $request) {
    if (! $request->user()) {
        return redirect()->route('login');
    }

    return match ($request->user()->role) {
        'hrd' => redirect()->route('divisions.index'),
        'division_manager' => redirect()->route('requisitions.index'),
        default => redirect()->route('applicant.jobs.index'),
    };
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::middleware('role:hrd')->group(function () {
        Route::get('/hrd', function () {
            return response()->json(['message' => 'hrd ok']);
        });

        Route::resource('divisions', DivisionController::class)->except(['show']);
        Route::resource('positions', PositionController::class)->except(['show']);

        Route::get('/job-postings/review', [JobPostingController::class, 'indexReview'])->name('job_postings.review');
        Route::post('/job-postings/review/{requisition}', [JobPostingController::class, 'processReview'])->name('job_postings.process_review');
        Route::resource('job-postings', JobPostingController::class)->except(['create', 'store', 'show']);
        Route::get('/hrd/applications', [ApplicationController::class, 'index'])->name('hrd.applications.index');
        Route::put('/hrd/applications/{application}/screening', [ApplicationScreeningController::class, 'update'])
            ->name('hrd.applications.screening.update');
        Route::post('/hrd/applications/{application}/hiring-decision', [HiringDecisionController::class, 'store'])
            ->name('hrd.applications.hiring-decision.store');
        Route::get('/hrd/interviews', [InterviewController::class, 'index'])->name('hrd.interviews.index');
        Route::post('/hrd/applications/{application}/interviews', [InterviewController::class, 'store'])
            ->name('hrd.applications.interviews.store');
        Route::put('/hrd/interviews/{interview}/evaluation', [InterviewController::class, 'evaluate'])
            ->name('hrd.interviews.evaluation.update');
    });

    Route::get('/applications/{application}/cv', [ApplicationController::class, 'downloadCv'])->name('applications.cv');

    // Route::get('/dashboard', function () {
    //     return response()->json(['message' => 'dashboard ok']);
    // });

    Route::middleware('role:division_manager')->group(function () {
        Route::get('/dashboard/manager', [DashboardController::class, 'manager'])->name('dashboard.manager');
        Route::get('/trash', [TrashController::class, 'index'])->name('trash.index');
        Route::post('/trash/requisitions/{id}/restore', [TrashController::class, 'restoreRequisition'])->name('trash.requisitions.restore');
        Route::resource('requisitions', RequisitionController::class)->except(['show']);
    });
    

    Route::middleware('role:applicant')->group(function () {
        Route::get('/jobs', [ApplicantJobPostingController::class, 'index'])->name('applicant.jobs.index');
        Route::get('/jobs/{jobPosting}', [ApplicantJobPostingController::class, 'show'])->name('applicant.jobs.show');
        Route::post('/jobs/{jobPosting}/applications', [ApplicationController::class, 'store'])->name('applications.store');
        Route::get('/applications', [ApplicationController::class, 'index'])->name('applications.index');
        Route::get('/profile', [ApplicantProfileController::class, 'edit'])->name('applicant.profile.edit');
        Route::put('/profile', [ApplicantProfileController::class, 'update'])->name('applicant.profile.update');
    });
});
