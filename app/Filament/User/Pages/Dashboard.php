<?php

namespace App\Filament\User\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static ?string $title = 'لوحة التحكم';

    public static function canAccess(): bool
    {
        return auth()->check();
    }

    public function getWidgets(): array
    {
        return [
            \App\Filament\User\Widgets\WelcomeWidget::class,
            \App\Filament\User\Widgets\UserStatsOverview::class,
            \App\Filament\User\Widgets\AdvancedStatsWidget::class,
            \App\Filament\User\Widgets\QuickActionsWidget::class,
            \App\Filament\User\Widgets\UserProgressWidget::class,
            \App\Filament\User\Widgets\RecentCvsWidget::class,
            \App\Filament\User\Widgets\SkillsOverview::class,
            \App\Filament\User\Widgets\AchievementsWidget::class,
            \App\Filament\User\Widgets\RecentActivityWidget::class,
        ];
    }

    public function getColumns(): int | string | array
    {
        return [
            'sm' => 1,
            'md' => 2,
            'lg' => 3,
            'xl' => 3,
        ];
    }
}
