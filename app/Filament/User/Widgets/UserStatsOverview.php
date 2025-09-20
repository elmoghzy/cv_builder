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

        return [
            Stat::make('سيرتي الذاتية', $totalCvs)
                ->description('عدد السير الذاتية')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('primary'),

            Stat::make('جاهزة للتحميل', $paidCvs)
                ->description('السير المدفوعة')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
        ];
    }

    protected function getColumns(): int
    {
        return 2;
    }
}
