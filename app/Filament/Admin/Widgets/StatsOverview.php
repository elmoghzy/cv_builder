<?php

namespace App\Filament\Admin\Widgets;

use App\Models\User;
use App\Models\Cv;
use App\Models\Payment;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        // Get payment statistics
        $totalRevenue = Payment::where('status', 'success')->sum('amount');
        $todayRevenue = Payment::where('status', 'success')
            ->whereDate('created_at', today())
            ->sum('amount');
        
        // Get user statistics
        $totalUsers = User::count();
        $newUsersToday = User::whereDate('created_at', today())->count();
        
        // Get CV statistics  
        $totalCvs = Cv::count();
        $paidCvs = Cv::where('is_paid', true)->count();
        
        return [
            Stat::make('Total Revenue', 'EGP ' . number_format($totalRevenue, 2))
                ->description('Today: EGP ' . number_format($todayRevenue, 2))
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
            
            Stat::make('Total Users', number_format($totalUsers))
                ->description($newUsersToday . ' new users today')
                ->descriptionIcon('heroicon-m-user-plus')
                ->color('primary'),
            
            Stat::make('Total CVs', number_format($totalCvs))
                ->description($paidCvs . ' paid CVs')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('info'),
            
            Stat::make('Conversion Rate', $totalCvs > 0 ? round(($paidCvs / $totalCvs) * 100, 2) . '%' : '0%')
                ->description('CVs converted to paid')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('warning'),
        ];
    }
}
