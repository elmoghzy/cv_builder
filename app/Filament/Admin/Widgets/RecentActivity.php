<?php

namespace App\Filament\Admin\Widgets;

use App\Models\User;
use App\Models\Cv;
use App\Models\Payment;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;

class RecentActivity extends BaseWidget
{
    protected static ?string $heading = 'Recent Activity';

    protected static ?int $sort = 5;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Cv::with(['user', 'template'])
                    ->latest()
                    ->limit(10)
            )
            ->columns([
                TextColumn::make('user.name')
                    ->label('User')
                    ->searchable(),
                
                TextColumn::make('title')
                    ->label('CV Title')
                    ->limit(30)
                    ->searchable(),
                
                TextColumn::make('template.name')
                    ->label('Template')
                    ->limit(20),
                
                BadgeColumn::make('status')
                    ->colors([
                        'gray' => 'draft',
                        'warning' => 'completed',
                        'success' => 'paid',
                    ]),
                
                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label('View')
                    ->icon('heroicon-o-eye')
                    ->url(fn (Cv $record): string => route('filament.admin.resources.cvs.view', $record))
                    ->openUrlInNewTab(),
            ]);
    }
}
