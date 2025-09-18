<?php

namespace App\Filament\User\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class WelcomeWidget extends Widget
{
    protected static string $view = 'filament.user.widgets.welcome';
    
    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = -10; // لجعلها تظهر في الأعلى

    public function getViewData(): array
    {
        /** @var User $user */
        $user = Auth::user();
        
        $timeOfDay = now()->hour;
        $greeting = '';
        
        if ($timeOfDay < 12) {
            $greeting = 'صباح الخير';
        } elseif ($timeOfDay < 17) {
            $greeting = 'مساء الخير';
        } else {
            $greeting = 'مساء الخير';
        }

        return [
            'user' => $user,
            'greeting' => $greeting,
            'totalCvs' => $user->cvs()->count(),
            'paidCvs' => $user->cvs()->where('is_paid', true)->count(),
        ];
    }
}
