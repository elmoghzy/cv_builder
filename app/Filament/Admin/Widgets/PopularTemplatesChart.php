<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Template;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class PopularTemplatesChart extends ChartWidget
{
    protected static ?string $heading = 'Most Popular Templates';

    protected static ?int $sort = 4;

    protected static ?string $maxHeight = '300px';

    public function getDescription(): ?string
    {
        return 'Top 5 most used templates';
    }

    protected function getData(): array
    {
        $data = Template::withCount('cvs')
            ->orderBy('cvs_count', 'desc')
            ->limit(5)
            ->get();

        $labels = $data->pluck('name')->toArray();
        $counts = $data->pluck('cvs_count')->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'CVs Created',
                    'data' => $counts,
                    'backgroundColor' => [
                        'rgba(16, 185, 129, 0.8)',
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(245, 158, 11, 0.8)',
                        'rgba(139, 92, 246, 0.8)',
                        'rgba(239, 68, 68, 0.8)',
                    ],
                    'borderColor' => [
                        'rgb(16, 185, 129)',
                        'rgb(59, 130, 246)',
                        'rgb(245, 158, 11)',
                        'rgb(139, 92, 246)',
                        'rgb(239, 68, 68)',
                    ],
                    'borderWidth' => 2,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'stepSize' => 1,
                    ],
                ],
            ],
            'maintainAspectRatio' => false,
        ];
    }
}
