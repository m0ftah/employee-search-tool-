<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Auth;

class Dashboard extends BaseDashboard
{
    public static function canAccess(): bool
    {
        $user = Auth::user();
        
        if (!$user) {
            return false;
        }
        
        // Allow all registered roles (Admin, HR, Candidate)
        return $user->hasRole('super_admin') || 
               $user->isAdmin() || 
               $user->isHR() || 
               $user->isCandidate();
    }
    
    public static function getNavigationLabel(): string
    {
        return __('app.dashboard');
    }
    
    public function getTitle(): string
    {
        return __('app.dashboard');
    }
    
    public function getHeading(): string | Htmlable
    {
        return __('app.dashboard');
    }
}
