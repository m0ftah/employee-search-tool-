<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

class CandidateWelcomeWidget extends Widget
{
    protected static string $view = 'filament.widgets.candidate-welcome-widget';

    protected static ?int $sort = -1;

    protected int | string | array $columnSpan = 'full';

    public static function canView(): bool
    {
        $user = Auth::user();
        return $user && method_exists($user, 'isCandidate') && $user->isCandidate();
    }
}
