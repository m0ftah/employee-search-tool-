<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Session;

class LanguageSwitcherWidget extends Widget
{
    protected static string $view = 'filament.widgets.language-switcher';
    
    protected int | string | array $columnSpan = 'full';
    
    protected static ?int $sort = 1;
    
    public function switchLanguage(string $locale): void
    {
        if (in_array($locale, ['en', 'ar'])) {
            Session::put('locale', $locale);
            app()->setLocale($locale);
            redirect(request()->header('Referer') ?? route('filament.admin.pages.dashboard'));
        }
    }
}

