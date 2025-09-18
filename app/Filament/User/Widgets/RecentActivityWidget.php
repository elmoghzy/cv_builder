<?php

namespace App\Filament\User\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;
use App\Models\Cv;
use App\Models\Payment;
use App\Models\User;

class RecentActivityWidget extends Widget
{
    protected static string $view = 'filament.user.widgets.recent-activity';
    
    protected int | string | array $columnSpan = [
        'md' => 2,
        'xl' => 1,
    ];

    public function getViewData(): array
    {
        /** @var User $user */
        $user = Auth::user();
        
        // آخر الأنشطة
        $recentCvs = $user->cvs()->latest()->take(3)->get();
        $recentPayments = $user->payments()->latest()->take(3)->get();
        
        $activities = collect();
        
        // إضافة السير الذاتية الحديثة
        foreach ($recentCvs as $cv) {
            $activities->push([
                'type' => 'cv_created',
                'title' => 'تم إنشاء سيرة ذاتية جديدة',
                'description' => $cv->title,
                'time' => $cv->created_at,
                'icon' => 'heroicon-o-document-text',
                'color' => 'primary',
            ]);
        }
        
        // إضافة المدفوعات الحديثة
        foreach ($recentPayments as $payment) {
            $activities->push([
                'type' => 'payment',
                'title' => $payment->status === 'success' ? 'تم الدفع بنجاح' : 'فشل في الدفع',
                'description' => 'مبلغ ' . number_format($payment->amount, 2) . ' جنيه',
                'time' => $payment->created_at,
                'icon' => $payment->status === 'success' ? 'heroicon-o-check-circle' : 'heroicon-o-x-circle',
                'color' => $payment->status === 'success' ? 'success' : 'danger',
            ]);
        }
        
        // ترتيب الأنشطة حسب التاريخ
        $activities = $activities->sortByDesc('time')->take(5);
        
        return [
            'activities' => $activities,
        ];
    }
}
