<?php

namespace App\Filament\Admin\Resources\PaymentResource\Pages;

use App\Filament\Admin\Resources\PaymentResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\IconEntry;

class ViewPayment extends ViewRecord
{
    protected static string $resource = PaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            
            Actions\Action::make('mark_paid')
                ->label('Mark as Paid')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->visible(fn (): bool => $this->record->status === 'pending')
                ->requiresConfirmation()
                ->action(function () {
                    $this->record->update(['status' => 'paid']);
                    if ($this->record->cv) {
                        $this->record->cv->update(['is_paid' => true, 'paid_at' => now()]);
                    }
                    $this->notify('Payment marked as paid successfully');
                }),
                
            Actions\Action::make('refund')
                ->label('Refund')
                ->icon('heroicon-o-arrow-uturn-left')
                ->color('warning')
                ->visible(fn (): bool => $this->record->status === 'paid')
                ->requiresConfirmation()
                ->action(function () {
                    $this->record->update(['status' => 'refunded']);
                    if ($this->record->cv) {
                        $this->record->cv->update(['is_paid' => false, 'paid_at' => null]);
                    }
                    $this->notify('Payment refunded successfully');
                }),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Payment Information')
                    ->schema([
                        TextEntry::make('user.name')
                            ->label('User'),
                        
                        TextEntry::make('cv.title')
                            ->label('CV Title'),
                        
                        TextEntry::make('amount')
                            ->money('EGP'),
                        
                        TextEntry::make('status')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'pending' => 'warning',
                                'paid' => 'success',
                                'failed' => 'danger',
                                'refunded' => 'gray',
                                default => 'gray',
                            }),
                    ])->columns(2),

                Section::make('Transaction Details')
                    ->schema([
                        TextEntry::make('payment_method')
                            ->label('Payment Method')
                            ->placeholder('Not specified'),
                        
                        TextEntry::make('transaction_id')
                            ->label('Transaction ID')
                            ->placeholder('Not available'),
                        
                        TextEntry::make('gateway_response')
                            ->label('Gateway Response')
                            ->placeholder('No response'),
                        
                        TextEntry::make('notes')
                            ->label('Admin Notes')
                            ->placeholder('No notes')
                            ->columnSpanFull(),
                    ])->columns(2),

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
