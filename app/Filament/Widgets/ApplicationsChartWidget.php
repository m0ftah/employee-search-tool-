<?php

namespace App\Filament\Widgets;

use App\Models\Application;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class ApplicationsChartWidget extends ChartWidget
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
        return __('app.applications_by_status');
    }

    protected function getData(): array
    {
        $applicationsByStatus = Application::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $labels = [];
        $data = [];
        $colors = [];

        $statusMap = [
            'pending' => ['label' => __('app.pending'), 'color' => 'rgba(251, 146, 60, 0.8)'],
            'reviewed' => ['label' => __('app.reviewed'), 'color' => 'rgba(59, 130, 246, 0.8)'],
            'shortlisted' => ['label' => __('app.shortlisted'), 'color' => 'rgba(34, 197, 94, 0.8)'],
            'rejected' => ['label' => __('app.rejected'), 'color' => 'rgba(239, 68, 68, 0.8)'],
            'hired' => ['label' => __('app.hired'), 'color' => 'rgba(168, 85, 247, 0.8)'],
        ];

        foreach ($statusMap as $status => $info) {
            $labels[] = $info['label'];
            $data[] = $applicationsByStatus[$status] ?? 0;
            $colors[] = $info['color'];
        }

        return [
            'datasets' => [
                [
                    'label' => __('app.applications'),
                    'data' => $data,
                    'backgroundColor' => $colors,
                    'borderColor' => $colors,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
