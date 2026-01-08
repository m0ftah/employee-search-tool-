<?php

namespace App\Filament\Widgets;

use App\Models\Application;
use App\Models\Candidate;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $candidatesCount = Candidate::count();
        $usersCount = User::count();
        $applicationsCount = Application::count();

        return [
            Stat::make(__('app.candidates'), $candidatesCount)
                ->description(__('app.total_candidates'))
                ->descriptionIcon('heroicon-o-user-circle')
                ->color('success')
                ->chart([7, 3, 4, 5, 6, 3, 5]),
            Stat::make(__('app.users'), $usersCount)
                ->description(__('app.total_users'))
                ->descriptionIcon('heroicon-o-users')
                ->color('info')
                ->chart([3, 2, 4, 5, 6, 4, 3]),
            Stat::make(__('app.applications'), $applicationsCount)
                ->description(__('app.total_applications'))
                ->descriptionIcon('heroicon-o-document-text')
                ->color('warning')
                ->chart([5, 4, 3, 6, 7, 5, 4]),
        ];
    }
}
