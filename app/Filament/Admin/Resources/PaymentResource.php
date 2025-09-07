<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PaymentResource\Pages;
use App\Filament\Admin\Resources\PaymentResource\RelationManagers;
use App\Models\Payment;
use App\Models\User;
use App\Models\Cv;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\DatePicker;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    protected static ?string $navigationLabel = 'Payments';

    protected static ?string $modelLabel = 'Payment';

    protected static ?string $pluralModelLabel = 'Payments';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationGroup = 'Financial';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Payment Details')
                    ->schema([
                        Select::make('user_id')
                            ->label('User')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        
                        Select::make('cv_id')
                            ->label('CV')
                            ->relationship('cv', 'title')
                            ->searchable()
                            ->preload()
                            ->required(),
                        
                        TextInput::make('amount')
                            ->required()
                            ->numeric()
                            ->prefix('EGP')
                            ->step(0.01),
                        
                        Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'paid' => 'Paid',
                                'failed' => 'Failed',
                                'refunded' => 'Refunded',
                            ])
                            ->required()
                            ->default('pending'),
                    ])->columns(2),

                Forms\Components\Section::make('Transaction Information')
                    ->schema([
                        TextInput::make('payment_method')
                            ->label('Payment Method')
                            ->maxLength(255),
                        
                        TextInput::make('transaction_id')
                            ->label('Transaction ID')
                            ->maxLength(255),
                        
                        TextInput::make('gateway_response')
                            ->label('Gateway Response')
                            ->maxLength(500),
                        
                        Textarea::make('notes')
                            ->label('Admin Notes')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->sortable(),
                
                TextColumn::make('user.name')
                    ->label('User')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('cv.title')
                    ->label('CV')
                    ->searchable()
                    ->limit(30),
                
                TextColumn::make('amount')
                    ->money('EGP')
                    ->sortable(),
                
                BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'paid',
                        'danger' => 'failed',
                        'gray' => 'refunded',
                    ]),
                
                TextColumn::make('payment_method')
                    ->label('Method'),
                
                TextColumn::make('transaction_id')
                    ->label('Transaction ID')
                    ->limit(20),
                
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('user')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),
                
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'Paid',
                        'failed' => 'Failed',
                        'refunded' => 'Refunded',
                    ]),
                
                SelectFilter::make('payment_method')
                    ->options([
                        'card' => 'Card',
                        'wallet' => 'Wallet',
                        'bank_transfer' => 'Bank Transfer',
                    ]),
                
                Filter::make('amount')
                    ->form([
                        TextInput::make('amount_from')
                            ->numeric()
                            ->label('Amount From'),
                        TextInput::make('amount_to')
                            ->numeric()
                            ->label('Amount To'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['amount_from'],
                                fn (Builder $query, $amount): Builder => $query->where('amount', '>=', $amount),
                            )
                            ->when(
                                $data['amount_to'],
                                fn (Builder $query, $amount): Builder => $query->where('amount', '<=', $amount),
                            );
                    }),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
                
                Action::make('mark_paid')
                    ->label('Mark as Paid')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Payment $record): bool => $record->status === 'pending')
                    ->requiresConfirmation()
                    ->action(function (Payment $record) {
                        $record->update(['status' => 'paid']);
                        // Additional logic for marking CV as paid
                        if ($record->cv) {
                            $record->cv->update(['is_paid' => true, 'paid_at' => now()]);
                        }
                    }),
                
                Action::make('refund')
                    ->label('Refund')
                    ->icon('heroicon-o-arrow-uturn-left')
                    ->color('warning')
                    ->visible(fn (Payment $record): bool => $record->status === 'paid')
                    ->requiresConfirmation()
                    ->action(function (Payment $record) {
                        $record->update(['status' => 'refunded']);
                        // Additional refund logic
                        if ($record->cv) {
                            $record->cv->update(['is_paid' => false, 'paid_at' => null]);
                        }
                    }),
                
                DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPayments::route('/'),
            'create' => Pages\CreatePayment::route('/create'),
            'view' => Pages\ViewPayment::route('/{record}'),
            'edit' => Pages\EditPayment::route('/{record}/edit'),
        ];
    }
}
