<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CandidateRegistrationController;
use Illuminate\Support\Facades\Session;

Route::get('/', function () {
    return view('welcome');
});

// Language Switcher
Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'ar'])) {
        Session::put('locale', $locale);
        app()->setLocale($locale);
    }
    return redirect()->back();
})->name('lang.switch');

// Candidate Registration Routes
Route::get('/candidate/register', [CandidateRegistrationController::class, 'showRegistrationForm'])->name('candidate.register.show');
Route::post('/candidate/register', [CandidateRegistrationController::class, 'register'])->name('candidate.register');
