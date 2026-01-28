<?php

namespace App\Filament\Auth;

use Filament\Facades\Filament;
use Filament\Http\Responses\Auth\Contracts\LoginResponse as LoginResponseContract;
use Illuminate\Http\RedirectResponse;
use Livewire\Features\SupportRedirects\Redirector;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request): RedirectResponse | Redirector
    {
        // Check if there's an intended URL in the session
        if (session()->has('url.intended')) {
            $intendedUrl = session()->pull('url.intended');
            return redirect()->to($intendedUrl);
        }

        // Get the authenticated user
        $user = auth()->user();

        // Redirect candidates to dashboard first
        if ($user && method_exists($user, 'isCandidate') && $user->isCandidate()) {
            return redirect()->to(\App\Filament\Pages\Dashboard::getUrl());
        }

        // Default redirect to panel home
        return redirect()->intended(Filament::getUrl());
    }
}
