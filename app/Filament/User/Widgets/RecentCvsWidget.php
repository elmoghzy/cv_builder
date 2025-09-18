<?php

namespace App\Filament\User\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Models\Cv;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Builder;

class RecentCvsWidget extends BaseWidget
{
    protected static ?string $heading = 'السير الذاتية الحديثة';
    
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Cv::query()
                    ->where('user_id', Auth::id())
                    ->latest()
                    ->limit(5)
            )
            ->columns([
                TextColumn::make('title')
                    ->label('العنوان')
                    ->searchable()
                    ->sortable()
                    ->weight('medium')
                    ->copyable(),

                TextColumn::make('template.name')
                    ->label('القالب')
                    ->badge()
                    ->color('primary'),

                BadgeColumn::make('is_paid')
                    ->label('الحالة')
                    ->formatStateUsing(fn ($state) => $state ? 'مدفوع' : 'معلق')
                    ->colors([
                        'success' => true,
                        'warning' => false,
                    ]),

                TextColumn::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                TextColumn::make('updated_at')
                    ->label('آخر تحديث')
                    ->since()
                    ->sortable(),
            ])
            ->actions([
                Action::make('view')
                    ->label('عرض')
                    ->icon('heroicon-o-eye')
                    ->url(fn (Cv $record): string => "/user/cvs/{$record->id}")
                    ->openUrlInNewTab(),
                    
                Action::make('download')
                    ->label('تحميل')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->url(fn (Cv $record): string => route('cv.download', $record))
                    ->visible(fn (Cv $record): bool => $record->is_paid)
                    ->color('success'),
            ])
            ->emptyStateHeading('لا توجد سير ذاتية')
            ->emptyStateDescription('ابدأ في إنشاء سيرتك الذاتية الأولى')
            ->emptyStateActions([
                Action::make('create')
                    ->label('إنشاء سيرة ذاتية جديدة')
                    ->url('/user/cvs/create')
                    ->icon('heroicon-o-plus'),
            ]);
    }
}
