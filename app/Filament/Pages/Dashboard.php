<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Auth;

class Dashboard extends BaseDashboard
{
    public static function canAccess(): bool
    {
        // Only allow Admin and HR to access the dashboard
        // Candidates should not see the dashboard
        $user = Auth::user();
        
        if (!$user) {
            return false;
        }
        
        // Allow super admin, admin, and HR
        if ($user->hasRole('super_admin') || $user->isAdmin() || $user->isHR()) {
            return true;
        }
        
        // Block candidates
        return false;
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
