<?php

namespace App\Filament\User\Widgets;

use Filament\Widgets\Widget;

class QuickActionsWidget extends Widget
{
    protected static string $view = 'filament.user.widgets.quick-actions';
    
    protected int | string | array $columnSpan = [
        'md' => 2,
        'xl' => 1,
    ];

    public function getViewData(): array
    {
        return [
            'actions' => [
                [
                    'title' => 'إنشاء سيرة ذاتية جديدة',
                    'description' => 'ابدأ في بناء سيرتك الذاتية باستخدام قوالبنا الاحترافية',
                    'url' => '/user/cvs/create',
                    'icon' => 'heroicon-o-plus',
                    'color' => 'primary',
                ],
                [
                    'title' => 'تصفح القوالب',
                    'description' => 'اختر من بين مجموعة متنوعة من القوالب المصممة بعناية',
                    'url' => '/user/cvs/create',
                    'icon' => 'heroicon-o-squares-2x2',
                    'color' => 'info',
                ],
                [
                    'title' => 'مساعد الذكي',
                    'description' => 'احصل على مساعدة من الذكاء الاصطناعي لتحسين سيرتك الذاتية',
                    'url' => '#',
                    'icon' => 'heroicon-o-light-bulb',
                    'color' => 'warning',
                    'onclick' => 'window.toggleChatbot()',
                ],
                [
                    'title' => 'حسابي',
                    'description' => 'إدارة معلوماتك الشخصية وإعدادات الحساب',
                    'url' => '/user/profile',
                    'icon' => 'heroicon-o-user-circle',
                    'color' => 'gray',
                ],
            ],
        ];
    }
}
