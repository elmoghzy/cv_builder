<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\TemplateResource\Pages;
use App\Filament\Admin\Resources\TemplateResource\RelationManagers;
use App\Models\Template;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\Action;

class TemplateResource extends Resource
{
    protected static ?string $model = Template::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-duplicate';

    protected static ?string $navigationLabel = 'Templates';

    protected static ?string $modelLabel = 'Template';

    protected static ?string $pluralModelLabel = 'Templates';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationGroup = 'Content Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Basic Information')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        
                        Textarea::make('description')
                            ->rows(3)
                            ->columnSpanFull(),
                        
                        Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),
                        
                        Toggle::make('is_premium')
                            ->label('Premium Template')
                            ->default(false),
                    ])->columns(2),

                Forms\Components\Section::make('Template Content')
                    ->schema([
                        Textarea::make('content')
                            ->label('HTML Content')
                            ->rows(10)
                            ->columnSpanFull()
                            ->helperText('HTML content of the template'),
                    ]),

                Forms\Components\Section::make('Template Styling')
                    ->schema([
                        Forms\Components\Select::make('styling.blade')
                            ->label('Blade Template')
                            ->options([
                                'cv.templates.ats-compliant' => 'ATS Compliant (Classic)',
                                'cv.templates.modern-one' => 'Modern One',
                            ])
                            ->default('cv.templates.ats-compliant'),
                        KeyValue::make('styling')
                            ->label('CSS Styling (JSON)')
                            ->columnSpanFull()
                            ->helperText('JSON object containing CSS properties and values'),
                    ]),

                Forms\Components\Section::make('Preview')
                    ->schema([
                        FileUpload::make('preview_image')
                            ->label('Preview Image')
                            ->image()
                            ->directory('template-previews')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->sortable(),
                
                ImageColumn::make('preview_image')
                    ->label('Preview')
                    ->size(50),
                
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                
                TextColumn::make('description')
                    ->limit(50)
                    ->wrap(),
                
                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
                
                IconColumn::make('is_premium')
                    ->label('Premium')
                    ->boolean()
                    ->trueColor('warning')
                    ->falseColor('gray'),
                
                TextColumn::make('cvs_count')
                    ->label('CVs Count')
                    ->counts('cvs')
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
                TernaryFilter::make('is_active')
                    ->label('Active Status'),
                
                TernaryFilter::make('is_premium')
                    ->label('Premium Status'),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
                
                Action::make('preview')
                    ->label('Preview')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->url(fn (Template $record): string => route('template.preview', $record))
                    ->openUrlInNewTab(),
                
                Action::make('duplicate')
                    ->label('Duplicate')
                    ->icon('heroicon-o-document-duplicate')
                    ->color('gray')
                    ->requiresConfirmation()
                    ->action(function (Template $record) {
                        $newTemplate = $record->replicate();
                        $newTemplate->name = $record->name . ' (Copy)';
                        $newTemplate->is_active = false;
                        $newTemplate->save();
                    }),
                
                DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    
                    Tables\Actions\BulkAction::make('activate')
                        ->label('Activate Selected')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(fn ($records) => $records->each->update(['is_active' => true])),
                    
                    Tables\Actions\BulkAction::make('deactivate')
                        ->label('Deactivate Selected')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->action(fn ($records) => $records->each->update(['is_active' => false])),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\CvsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTemplates::route('/'),
            'create' => Pages\CreateTemplate::route('/create'),
            'view' => Pages\ViewTemplate::route('/{record}'),
            'edit' => Pages\EditTemplate::route('/{record}/edit'),
        ];
    }
}
