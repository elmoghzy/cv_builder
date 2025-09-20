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
            \App\Filament\User\Widgets\ModernDashboardWidget::class,
        ];
    }

    public function getColumns(): int | string | array
    {
        return 1; // Single column layout for modern design
    }
}
