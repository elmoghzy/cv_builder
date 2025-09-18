<?php

namespace App\Filament\User\Widgets;

use App\Models\User;
use Filament\Widgets\Widget;
use Carbon\Carbon;

class AdvancedStatsWidget extends Widget
{
    protected static string $view = 'filament.user.widgets.advanced-stats';

    protected int | string | array $columnSpan = 'full';

    protected function getViewData(): array
    {
        /** @var User $user */
        $user = auth()->user();

        // Calculate various statistics
        $totalCvs = $user->cvs()->count();
        $paidCvs = $user->cvs()->where('is_paid', true)->count();
        $thisMonthCvs = $user->cvs()->whereMonth('created_at', now()->month)->count();
        $totalSpent = $user->payments()->where('status', 'success')->sum('amount');
        
        // Calculate completion rate
        $completionRate = $totalCvs > 0 ? round(($paidCvs / $totalCvs) * 100) : 0;
        
        // Get activity this week
        $weekStart = now()->startOfWeek();
        $weekActivity = $user->cvs()->where('created_at', '>=', $weekStart)->count();
        
        // Growth compared to last month
        $lastMonthCvs = $user->cvs()
            ->whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->count();
            
        $growth = $lastMonthCvs > 0 ? round((($thisMonthCvs - $lastMonthCvs) / $lastMonthCvs) * 100) : 0;

        return [
            'stats' => [
                [
                    'label' => 'إجمالي السير الذاتية',
                    'value' => $totalCvs,
                    'icon' => '📄',
                    'color' => 'blue',
                    'change' => $growth,
                    'changeLabel' => 'مقارنة بالشهر الماضي'
                ],
                [
                    'label' => 'السير المدفوعة',
                    'value' => $paidCvs,
                    'icon' => '💳',
                    'color' => 'green',
                    'change' => $completionRate,
                    'changeLabel' => 'معدل الإكمال'
                ],
                [
                    'label' => 'النشاط هذا الأسبوع',
                    'value' => $weekActivity,
                    'icon' => '🔥',
                    'color' => 'orange',
                    'change' => $weekActivity > 0 ? '+' . $weekActivity : 0,
                    'changeLabel' => 'سير ذاتية جديدة'
                ],
                [
                    'label' => 'إجمالي الإنفاق',
                    'value' => number_format($totalSpent, 2) . ' ج.م',
                    'icon' => '💰',
                    'color' => 'purple',
                    'change' => $totalSpent > 0 ? 'مدفوع' : 'مجاني',
                    'changeLabel' => 'حالة العضوية'
                ]
            ]
        ];
    }
}
