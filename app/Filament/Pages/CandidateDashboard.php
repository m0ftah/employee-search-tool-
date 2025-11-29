<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class CandidateDashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static string $view = 'filament.pages.candidate-dashboard';

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->hasRole('candidate');
    }

    public function mount(): void
    {
        abort_unless(auth()->user()->hasRole('candidate'), 403);
    }
}
