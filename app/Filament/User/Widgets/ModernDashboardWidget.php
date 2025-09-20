<?php

namespace App\Filament\User\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;
use App\Models\Cv;
use App\Models\User;

class ModernDashboardWidget extends Widget
{
    protected static string $view = 'filament.user.widgets.modern-dashboard';
    
    protected int | string | array $columnSpan = 1;
    
    protected static bool $isLazy = false;
    
    protected static ?int $sort = -10;

    public function getViewData(): array
    {
        /** @var User $user */
        $user = Auth::user();
        
        $totalCvs = Cv::where('user_id', $user->id)->count();
        $paidCvs = Cv::where('user_id', $user->id)->where('is_paid', true)->count();
        $recentCvs = Cv::where('user_id', $user->id)->latest()->take(3)->get();

        return [
            'user' => $user,
            'totalCvs' => $totalCvs,
            'paidCvs' => $paidCvs,
            'recentCvs' => $recentCvs,
        ];
    }
}
