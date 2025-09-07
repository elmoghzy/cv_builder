<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Payment;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RevenueChart extends ChartWidget
{
    protected static ?string $heading = 'Revenue Overview';

    protected static ?int $sort = 2;

    protected static ?string $maxHeight = '300px';

    public function getDescription(): ?string
    {
        return 'Daily revenue for the last 30 days';
    }

    protected function getData(): array
    {
        $data = Payment::where('status', 'paid')
            ->where('created_at', '>=', now()->subDays(30))
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(amount) as revenue')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $labels = [];
        $revenues = [];

        // Fill in missing dates with 0 revenue
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $labels[] = now()->subDays($i)->format('M d');
            
            $dayRevenue = $data->where('date', $date)->first();
            $revenues[] = $dayRevenue ? (float) $dayRevenue->revenue : 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Daily Revenue (EGP)',
                    'data' => $revenues,
                    'borderColor' => 'rgb(16, 185, 129)',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                    'fill' => true,
                    'tension' => 0.4,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
