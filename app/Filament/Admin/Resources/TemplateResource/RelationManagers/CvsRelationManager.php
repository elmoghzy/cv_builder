<?php

namespace App\Filament\Admin\Resources\TemplateResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\IconColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CvsRelationManager extends RelationManager
{
    protected static string $relationship = 'cvs';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                
                Forms\Components\Select::make('user_id')
                    ->label('User')
                    ->relationship('user', 'name')
                    ->required(),
                
                Forms\Components\Select::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'completed' => 'Completed',
                        'paid' => 'Paid',
                    ])
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                TextColumn::make('id')
                    ->sortable(),
                
                TextColumn::make('title')
                    ->searchable()
                    ->limit(30),
                
                TextColumn::make('user.name')
                    ->label('User')
                    ->searchable(),
                
                BadgeColumn::make('status')
                    ->colors([
                        'gray' => 'draft',
                        'warning' => 'completed',
                        'success' => 'paid',
                    ]),
                
                IconColumn::make('is_paid')
                    ->label('Paid')
                    ->boolean(),
                
                TextColumn::make('download_count')
                    ->label('Downloads'),
                
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'completed' => 'Completed',
                        'paid' => 'Paid',
                    ]),
                
                Tables\Filters\TernaryFilter::make('is_paid')
                    ->label('Payment Status'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
