<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class Chat extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected static string $view = 'filament.pages.chat';

    protected static ?string $navigationLabel = null;

    public static function getNavigationLabel(): string
    {
        return __('app.chat');
    }

    protected static ?string $navigationGroup = null;

    public static function getNavigationGroup(): ?string
    {
        return __('app.user_management');
    }

    public static function shouldRegisterNavigation(): bool
    {
        $user = Auth::user();
        return $user && ($user->isCandidate() || $user->isHR());
    }

    public function getTitle(): string
    {
        return __('app.chat');
    }
}
