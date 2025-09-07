<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Cv;
use App\Models\Template;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class CvStatusChart extends ChartWidget
{
    protected static ?string $heading = 'CV Status Distribution';

    protected static ?int $sort = 3;

    protected static ?string $maxHeight = '300px';

    public function getDescription(): ?string
    {
        return 'Distribution of CV statuses';
    }

    protected function getData(): array
    {
        $data = Cv::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();

        $labels = [];
        $counts = [];
        $colors = [];

        foreach ($data as $item) {
            $labels[] = ucfirst($item->status);
            $counts[] = $item->count;
            
            // Assign colors based on status
            $colors[] = match($item->status) {
                'draft' => '#6B7280',
                'completed' => '#F59E0B',
                'paid' => '#10B981',
                default => '#8B5CF6'
            };
        }

        return [
            'datasets' => [
                [
                    'data' => $counts,
                    'backgroundColor' => $colors,
                    'borderWidth' => 2,
                    'borderColor' => '#ffffff',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                ],
            ],
            'maintainAspectRatio' => false,
        ];
    }
}
