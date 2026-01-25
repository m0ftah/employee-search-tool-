<?php

namespace App\Providers;

use App\Filament\Auth\LoginResponse;
use App\Listeners\SendChatMessageEmailNotification;
use Filament\Http\Responses\Auth\Contracts\LoginResponse as LoginResponseContract;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Namu\WireChat\Events\NotifyParticipant;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind custom login response
        $this->app->bind(LoginResponseContract::class, LoginResponse::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register Wirechat message event listener
        Event::listen(
            NotifyParticipant::class,
            SendChatMessageEmailNotification::class
        );
    }
}
