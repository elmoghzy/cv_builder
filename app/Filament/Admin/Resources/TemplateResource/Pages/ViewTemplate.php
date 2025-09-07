<?php

namespace App\Filament\Admin\Resources\TemplateResource\Pages;

use App\Filament\Admin\Resources\TemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\KeyValueEntry;

class ViewTemplate extends ViewRecord
{
    protected static string $resource = TemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            
            Actions\Action::make('preview')
                ->label('Preview Template')
                ->icon('heroicon-o-eye')
                ->color('info')
                ->url(fn (): string => route('template.preview', $this->record))
                ->openUrlInNewTab(),
                
            Actions\Action::make('duplicate')
                ->label('Duplicate Template')
                ->icon('heroicon-o-document-duplicate')
                ->color('gray')
                ->requiresConfirmation()
                ->action(function () {
                    $newTemplate = $this->record->replicate();
                    $newTemplate->name = $this->record->name . ' (Copy)';
                    $newTemplate->is_active = false;
                    $newTemplate->save();
                    $this->notify('Template duplicated successfully');
                }),
                
            Actions\Action::make('toggle_status')
                ->label(fn (): string => $this->record->is_active ? 'Deactivate' : 'Activate')
                ->icon(fn (): string => $this->record->is_active ? 'heroicon-o-x-circle' : 'heroicon-o-check-circle')
                ->color(fn (): string => $this->record->is_active ? 'danger' : 'success')
                ->requiresConfirmation()
                ->action(function () {
                    $this->record->update(['is_active' => !$this->record->is_active]);
                    $status = $this->record->is_active ? 'activated' : 'deactivated';
                    $this->notify("Template {$status} successfully");
                }),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Basic Information')
                    ->schema([
                        TextEntry::make('name')
                            ->weight('bold'),
                        
                        TextEntry::make('description')
                            ->placeholder('No description'),
                        
                        IconEntry::make('is_active')
                            ->label('Status')
                            ->boolean()
                            ->trueIcon('heroicon-o-check-circle')
                            ->falseIcon('heroicon-o-x-circle')
                            ->trueColor('success')
                            ->falseColor('danger'),
                        
                        IconEntry::make('is_premium')
                            ->label('Premium')
                            ->boolean()
                            ->trueColor('warning')
                            ->falseColor('gray'),
                    ])->columns(2),

                Section::make('Preview')
                    ->schema([
                        ImageEntry::make('preview_image')
                            ->label('Template Preview')
                            ->size(300)
                            ->placeholder('No preview image'),
                    ])->visible(fn (): bool => (bool) $this->record->preview_image),

                Section::make('Usage Statistics')
                    ->schema([
                        TextEntry::make('cvs_count')
                            ->label('Total CVs Created')
                            ->getStateUsing(fn (): int => $this->record->cvs()->count()),
                        
                        TextEntry::make('paid_cvs_count')
                            ->label('Paid CVs')
                            ->getStateUsing(fn (): int => $this->record->cvs()->where('is_paid', true)->count()),
                        
                        TextEntry::make('revenue')
                            ->label('Total Revenue')
                            ->money('EGP')
                            ->getStateUsing(function (): float {
                                return $this->record->cvs()
                                    ->where('is_paid', true)
                                    ->join('payments', 'cvs.id', '=', 'payments.cv_id')
                                    ->where('payments.status', 'paid')
                                    ->sum('payments.amount');
                            }),
                    ])->columns(3),

                Section::make('Template Content')
                    ->schema([
                        TextEntry::make('content')
                            ->label('HTML Content')
                            ->placeholder('No content')
                            ->columnSpanFull()
                            ->limit(500),
                    ]),

                Section::make('Styling')
                    ->schema([
                        KeyValueEntry::make('styling')
                            ->label('CSS Styling (JSON)')
                            ->placeholder('No styling defined')
                            ->columnSpanFull(),
                    ]),

                Section::make('Timestamps')
                    ->schema([
                        TextEntry::make('created_at')
                            ->dateTime(),
                        
                        TextEntry::make('updated_at')
                            ->dateTime(),
                    ])->columns(2),
            ]);
    }
}
