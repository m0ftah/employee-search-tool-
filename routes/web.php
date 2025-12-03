<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CandidateRegistrationController;

Route::get('/', function () {
    return view('welcome');
});

// Candidate Registration Routes
Route::get('/candidate/register', [CandidateRegistrationController::class, 'showRegistrationForm'])->name('candidate.register.show');
Route::post('/candidate/register', [CandidateRegistrationController::class, 'register'])->name('candidate.register');
