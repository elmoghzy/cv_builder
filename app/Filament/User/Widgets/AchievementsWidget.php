<?php

namespace App\Filament\User\Widgets;

use App\Models\User;
use Filament\Widgets\Widget;

class AchievementsWidget extends Widget
{
    protected static string $view = 'filament.user.widgets.achievements';

    protected int | string | array $columnSpan = 'full';

    protected function getViewData(): array
    {
        /** @var User $user */
        $user = auth()->user();

        $achievements = [
            [
                'icon' => '🎯',
                'title' => 'صاحب سيرة ذاتية',
                'description' => 'قمت بإنشاء أول سيرة ذاتية',
                'completed' => $user->cvs()->count() > 0,
                'progress' => min(100, $user->cvs()->count() * 100)
            ],
            [
                'icon' => '📊',
                'title' => 'محترف السير الذاتية',
                'description' => 'أنشأت 3 سير ذاتية أو أكثر',
                'completed' => $user->cvs()->count() >= 3,
                'progress' => min(100, ($user->cvs()->count() / 3) * 100)
            ],
            [
                'icon' => '💳',
                'title' => 'عضو مدفوع',
                'description' => 'قمت بأول عملية دفع',
                'completed' => $user->payments()->where('status', 'success')->count() > 0,
                'progress' => $user->payments()->where('status', 'success')->count() > 0 ? 100 : 0
            ],
            [
                'icon' => '⭐',
                'title' => 'النجم',
                'description' => 'حصلت على 5 سير ذاتية مدفوعة',
                'completed' => $user->cvs()->where('is_paid', true)->count() >= 5,
                'progress' => min(100, ($user->cvs()->where('is_paid', true)->count() / 5) * 100)
            ],
        ];

        return [
            'achievements' => $achievements,
            'completedCount' => collect($achievements)->where('completed', true)->count(),
            'totalCount' => count($achievements)
        ];
    }
}
