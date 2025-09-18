<?php

namespace App\Filament\User\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;
use App\Models\Cv;
use App\Models\Payment;
use App\Models\User;

class UserStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        /** @var User $user */
        $user = Auth::user();
        
        $totalCvs = $user->cvs()->count();
        $paidCvs = $user->cvs()->where('is_paid', true)->count();
        $totalSpent = $user->payments()->where('status', 'success')->sum('amount');
        $pendingCvs = $user->cvs()->where('is_paid', false)->count();

        return [
            Stat::make('إجمالي السير الذاتية', $totalCvs)
                ->description('العدد الكلي للسير الذاتية المنشأة')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('primary')
                ->chart([7, 2, 10, 3, 15, 4, 17]),

            Stat::make('السير الذاتية المدفوعة', $paidCvs)
                ->description('السير الذاتية الجاهزة للتحميل')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success')
                ->chart([3, 1, 4, 2, 5, 3, 6]),

            Stat::make('السير الذاتية المعلقة', $pendingCvs)
                ->description('في انتظار الدفع')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning')
                ->chart([1, 3, 2, 4, 1, 2, 3]),

            Stat::make('إجمالي المصروف', number_format($totalSpent, 2) . ' جنيه')
                ->description('المبلغ الكلي المدفوع')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('info')
                ->chart([10, 20, 15, 30, 25, 40, 35]),
        ];
    }

    protected function getColumns(): int
    {
        return 2;
    }
}
