<?php

namespace App\Providers;

use App\Listeners\SendChatMessageEmailNotification;
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
        //
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
