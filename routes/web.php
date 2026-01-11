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

// Chat Routes
Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/start-chat/{type}/{id}', function ($type, $id) {
        $user = auth()->user();
        
        if (!$user) {
            abort(401, 'You must be logged in.');
        }
        
        // Load the record with user relationship
        if ($type === 'hr') {
            $record = \App\Models\HR::with('user')->findOrFail($id);
            if (!$user->isCandidate()) {
                abort(403, 'Only candidates can chat with HR.');
            }
        } elseif ($type === 'candidate') {
            $record = \App\Models\Candidate::with('user')->findOrFail($id);
            if (!$user->isHR()) {
                abort(403, 'Only HR can chat with candidates.');
            }
        } else {
            abort(404, 'Invalid chat type.');
        }
        
        // Ensure user relationship exists
        if (!$record->user) {
            abort(404, 'User not found for this record.');
        }
        
        try {
            // Ensure current user can chat
            if (!$user->canCreateChats()) {
                abort(403, 'You do not have permission to create chats. Your user type: ' . $user->type);
            }
            
            $participantUser = $record->user;
            
            // Ensure participant has Chatable trait
            if (!in_array(\Namu\WireChat\Traits\Chatable::class, class_uses($participantUser))) {
                abort(403, 'The selected user does not support chat functionality.');
            }
            
            // Create or get existing conversation
            $conversation = $user->createConversationWith($participantUser);
            
            if (!$conversation) {
                abort(500, 'Failed to create conversation.');
            }
            
            $prefix = config('wirechat.routes.prefix', 'chats');
            
            return redirect("/{$prefix}/{$conversation->id}");
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->back()->with('error', 'Record not found.');
        } catch (\Exception $e) {
            \Log::error('Chat creation error: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'type' => $type,
                'id' => $id,
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()->with('error', 'Error creating chat: ' . $e->getMessage());
        }
    })->name('chat.start');
});
