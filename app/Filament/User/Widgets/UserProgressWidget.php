<?php

namespace App\Filament\User\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserProgressWidget extends Widget
{
    protected static string $view = 'filament.user.widgets.user-progress';
    
    protected int | string | array $columnSpan = 'full';

    public function getViewData(): array
    {
        /** @var User $user */
        $user = Auth::user();
        
        $totalCvs = $user->cvs()->count();
        $paidCvs = $user->cvs()->where('is_paid', true)->count();
        
        // حساب مستوى التقدم
        $steps = [
            [
                'title' => 'إنشاء الحساب',
                'description' => 'مرحباً بك في منصتنا',
                'completed' => true,
                'icon' => 'heroicon-o-user-plus',
            ],
            [
                'title' => 'إنشاء أول سيرة ذاتية',
                'description' => 'ابدأ رحلتك المهنية',
                'completed' => $totalCvs > 0,
                'icon' => 'heroicon-o-document-text',
            ],
            [
                'title' => 'دفع أول سيرة ذاتية',
                'description' => 'احصل على نسختك النهائية',
                'completed' => $paidCvs > 0,
                'icon' => 'heroicon-o-credit-card',
            ],
            [
                'title' => 'تحميل السيرة الذاتية',
                'description' => 'ابدأ في التقديم للوظائف',
                'completed' => $paidCvs > 0,
                'icon' => 'heroicon-o-arrow-down-tray',
            ],
            [
                'title' => 'إنشاء عدة سير ذاتية',
                'description' => 'قوالب متنوعة لتخصصات مختلفة',
                'completed' => $totalCvs >= 3,
                'icon' => 'heroicon-o-squares-2x2',
            ],
        ];
        
        $completedSteps = collect($steps)->where('completed', true)->count();
        $progressPercentage = ($completedSteps / count($steps)) * 100;
        
        return [
            'steps' => $steps,
            'completedSteps' => $completedSteps,
            'totalSteps' => count($steps),
            'progressPercentage' => $progressPercentage,
        ];
    }
}
