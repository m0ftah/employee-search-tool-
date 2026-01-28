<?php

namespace App\Filament\Widgets;

use App\Models\Application;
use App\Models\Candidate;
use App\Models\User;
use Filament\Widgets\ChartWidget;

class StatsChartWidget extends ChartWidget
{
    public static function canView(): bool
    {
        $user = auth()->user();
        return $user && ($user->isAdmin() || $user->isHR());
    }

    protected static ?string $heading = null;
    
    protected int | string | array $columnSpan = 1;
    
    protected static ?string $maxHeight = '400px';
    
    protected function getOptions(): array
    {
        return [
            'maintainAspectRatio' => true,
            'aspectRatio' => 2,
        ];
    }
    
    public function getHeading(): string
    {
        return __('app.statistics_overview');
    }

    protected function getData(): array
    {
        $candidatesCount = Candidate::count();
        $usersCount = User::count();
        $applicationsCount = Application::count();

        return [
            'datasets' => [
                [
                    'label' => __('app.candidates'),
                    'data' => [$candidatesCount],
                    'backgroundColor' => 'rgba(34, 197, 94, 0.2)',
                    'borderColor' => 'rgb(34, 197, 94)',
                ],
                [
                    'label' => __('app.users'),
                    'data' => [$usersCount],
                    'backgroundColor' => 'rgba(59, 130, 246, 0.2)',
                    'borderColor' => 'rgb(59, 130, 246)',
                ],
                [
                    'label' => __('app.applications'),
                    'data' => [$applicationsCount],
                    'backgroundColor' => 'rgba(251, 146, 60, 0.2)',
                    'borderColor' => 'rgb(251, 146, 60)',
                ],
            ],
            'labels' => [__('app.statistics')],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
