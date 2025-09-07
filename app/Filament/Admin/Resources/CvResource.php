<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\CvResource\Pages;
use App\Filament\Admin\Resources\CvResource\RelationManagers;
use App\Models\Cv;
use App\Models\User;
use App\Models\Template;
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
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\DateTimePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;

class CvResource extends Resource
{
    protected static ?string $model = Cv::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'CVs';

    protected static ?string $modelLabel = 'CV';

    protected static ?string $pluralModelLabel = 'CVs';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationGroup = 'Content Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Basic Information')
                    ->schema([
                        Select::make('user_id')
                            ->label('User')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        
                        Select::make('template_id')
                            ->label('Template')
                            ->relationship('template', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255),
                        
                        Select::make('status')
                            ->options([
                                'draft' => 'Draft',
                                'completed' => 'Completed',
                                'paid' => 'Paid',
                            ])
                            ->required()
                            ->default('draft'),
                    ])->columns(2),

                Forms\Components\Section::make('CV Content')
                    ->schema([
                        KeyValue::make('content')
                            ->label('CV Content (JSON)')
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Status & Tracking')
                    ->schema([
                        Toggle::make('is_paid')
                            ->label('Is Paid')
                            ->disabled(),
                        
                        TextInput::make('download_count')
                            ->label('Download Count')
                            ->numeric()
                            ->default(0)
                            ->disabled(),
                        
                        TextInput::make('pdf_path')
                            ->label('PDF Path')
                            ->disabled(),
                        
                        DateTimePicker::make('paid_at')
                            ->label('Paid At')
                            ->disabled(),
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
                
                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(30),
                
                TextColumn::make('template.name')
                    ->label('Template')
                    ->sortable(),
                
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
                    ->label('Downloads')
                    ->sortable(),
                
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
                
                SelectFilter::make('template')
                    ->relationship('template', 'name'),
                
                SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'completed' => 'Completed',
                        'paid' => 'Paid',
                    ]),
                
                TernaryFilter::make('is_paid')
                    ->label('Payment Status'),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
                
                Action::make('download_pdf')
                    ->label('Download PDF')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->visible(fn (Cv $record): bool => $record->is_paid && $record->pdf_path)
                    ->url(fn (Cv $record): string => asset('storage/' . $record->pdf_path))
                    ->openUrlInNewTab(),
                
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
            RelationManagers\PaymentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCvs::route('/'),
            'create' => Pages\CreateCv::route('/create'),
            'view' => Pages\ViewCv::route('/{record}'),
            'edit' => Pages\EditCv::route('/{record}/edit'),
        ];
    }
}
