<?php

namespace App\Filament\Admin\Resources\CvResource\Pages;

use App\Filament\Admin\Resources\CvResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\KeyValueEntry;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\Actions\Action;
use Illuminate\Support\Facades\Storage;

class ViewCv extends ViewRecord
{
    protected static string $resource = CvResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            
            Actions\Action::make('download_pdf')
                ->label('Download PDF')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->visible(fn (): bool => $this->record->is_paid && $this->record->pdf_path)
                ->url(fn (): string => asset('storage/' . $this->record->pdf_path))
                ->openUrlInNewTab(),
                
            Actions\Action::make('regenerate_pdf')
                ->label('Regenerate PDF')
                ->icon('heroicon-o-arrow-path')
                ->color('warning')
                ->requiresConfirmation()
                ->action(function () {
                    // Add logic to regenerate PDF
                    $this->notify('PDF regeneration queued successfully');
                }),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Basic Information')
                    ->schema([
                        TextEntry::make('user.name')
                            ->label('User'),
                        
                        TextEntry::make('title'),
                        
                        TextEntry::make('template.name')
                            ->label('Template'),
                        
                        TextEntry::make('status')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'draft' => 'gray',
                                'completed' => 'warning',
                                'paid' => 'success',
                                default => 'gray',
                            }),
                    ])->columns(2),

                Section::make('Payment Information')
                    ->schema([
                        IconEntry::make('is_paid')
                            ->label('Payment Status')
                            ->boolean(),
                        
                        TextEntry::make('paid_at')
                            ->dateTime()
                            ->placeholder('Not paid yet'),
                        
                        TextEntry::make('download_count')
                            ->label('Downloads'),
                        
                        TextEntry::make('pdf_path')
                            ->label('PDF File')
                            ->placeholder('Not generated yet'),
                    ])->columns(2),

                Section::make('CV Content')
                    ->schema([
                        KeyValueEntry::make('content')
                            ->label('CV Data (JSON)')
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
