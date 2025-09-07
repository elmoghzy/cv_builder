<?php

namespace App\Filament\User\Resources;

use App\Filament\User\Resources\CvResource\Pages;
use App\Models\Cv;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\TagsInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Builder;
use App\Services\PayMobService;
use App\Models\Payment;

class CvResource extends Resource
{
    protected static ?string $model = Cv::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $modelLabel = 'My CV';

    protected static ?string $pluralModelLabel = 'My CVs';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Wizard\Step::make('Basics')
                        ->schema([
                            TextInput::make('title')
                                ->label('CV Title')
                                ->required()
                                ->maxLength(255),
                            Select::make('template_id')
                                ->relationship('template', 'name')
                                ->label('Template')
                                ->required()
                                ->preload()
                                ->searchable(),
                        ])->columns(2),

                    Wizard\Step::make('Personal Info')
                        ->schema([
                            Group::make()->statePath('content.personal_info')->schema([
                                TextInput::make('full_name')->required()->maxLength(120),
                                TextInput::make('email')->email()->maxLength(150),
                                TextInput::make('phone')->tel()->maxLength(50),
                                TextInput::make('address')->maxLength(200),
                                TextInput::make('linkedin')->label('LinkedIn URL')->url()->maxLength(200),
                                TextInput::make('website')->label('Website/Portfolio')->url()->maxLength(200),
                            ])->columns(2),
                        ]),

                    Wizard\Step::make('Summary')
                        ->schema([
                            Group::make()->statePath('content')->schema([
                                Textarea::make('professional_summary')->label('Professional Summary')->rows(5)->maxLength(1000),
                                Textarea::make('objective')->label('Career Objective')->rows(4)->maxLength(500),
                            ]),
                        ]),

                    Wizard\Step::make('Experience')
                        ->schema([
                            Repeater::make('work_experience')
                                ->statePath('content.work_experience')
                                ->label('Work Experience')
                                ->schema([
                                    TextInput::make('job_title')->required()->maxLength(120),
                                    TextInput::make('company')->required()->maxLength(120),
                                    TextInput::make('location')->maxLength(120),
                                    DatePicker::make('start_date')->native(false),
                                    DatePicker::make('end_date')->native(false)->hidden(fn ($get) => (bool) $get('current')),
                                    Toggle::make('current')->live()->inline(false)->default(false),
                                    Textarea::make('description')->rows(4)->columnSpanFull(),
                                    Textarea::make('achievements')->rows(3)->columnSpanFull()->label('Key Achievements'),
                                ])->columns(2)->addActionLabel('Add position')
                                ->itemLabel(fn (array $state): ?string => $state['job_title'] ?? null),
                        ]),

                    Wizard\Step::make('Education')
                        ->schema([
                            Repeater::make('education')
                                ->statePath('content.education')
                                ->label('Education')
                                ->schema([
                                    TextInput::make('degree')->required()->maxLength(120),
                                    TextInput::make('institution')->required()->maxLength(120),
                                    TextInput::make('location')->maxLength(120),
                                    DatePicker::make('graduation_date')->native(false),
                                    TextInput::make('gpa')->maxLength(20),
                                    Textarea::make('honors')->rows(2)->columnSpanFull(),
                                ])->columns(2)->addActionLabel('Add education'),
                        ]),

                    Wizard\Step::make('Skills')
                        ->schema([
                            Group::make()->statePath('content')->schema([
                                TagsInput::make('technical_skills')->label('Technical Skills')->separator(','),
                                TagsInput::make('soft_skills')->label('Soft Skills')->separator(','),
                                TagsInput::make('languages')->label('Languages')->separator(','),
                            ])->columns(1),
                        ]),

                    Wizard\Step::make('Projects & Certs')
                        ->schema([
                            Repeater::make('projects')
                                ->statePath('content.projects')
                                ->label('Projects')
                                ->schema([
                                    TextInput::make('project_name')->required()->maxLength(150),
                                    Textarea::make('description')->rows(3)->columnSpanFull(),
                                    TextInput::make('technologies')->helperText('Comma-separated')->maxLength(200),
                                    TextInput::make('duration')->maxLength(100),
                                    TextInput::make('url')->url()->maxLength(200),
                                ])->columns(2)->addActionLabel('Add project'),

                            Repeater::make('certifications')
                                ->statePath('content.certifications')
                                ->label('Certifications')
                                ->schema([
                                    TextInput::make('name')->required()->maxLength(150),
                                    TextInput::make('issuer')->maxLength(150),
                                    DatePicker::make('date')->native(false),
                                    TextInput::make('credential_id')->label('Credential ID')->maxLength(120),
                                ])->columns(2)->addActionLabel('Add certification'),
                        ]),
                ])
                ->skippable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->where('user_id', auth()->id()))
            ->columns([
                TextColumn::make('title')->searchable()->limit(30),
                TextColumn::make('template.name')->label('Template'),
                BadgeColumn::make('status')->colors([
                    'gray' => 'draft',
                    'warning' => 'completed',
                    'success' => 'paid',
                ]),
                TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->actions([
                Action::make('preview')
                    ->label('View')
                    ->icon('heroicon-o-eye')
                    ->url(fn (Cv $record): string => route('cv.preview', $record))
                    ->openUrlInNewTab(),
                Tables\Actions\EditAction::make(),
                Action::make('pay_now')
                    ->label('Pay Now')
                    ->icon('heroicon-o-credit-card')
                    ->color('success')
                    ->visible(fn (Cv $record): bool => !$record->is_paid && $record->status !== 'paid')
                    ->action(function (Cv $record) {
                        // Create pending payment and get PayMob URL
                        $amountCents = (int) (config('paymob.cv_price_cents', 5000)); // default 50 EGP

                        $service = app(PayMobService::class);
                        $paymentData = [
                            'amount' => $amountCents,
                            'currency' => 'EGP',
                            'items' => [[
                                'name' => 'CV Purchase',
                                'amount_cents' => $amountCents,
                                'quantity' => 1,
                            ]],
                            'order_id' => 'cv-' . $record->id . '-' . time(),
                        ];

                        $billing = [
                            'apartment' => 'NA', 'email' => auth()->user()->email, 'floor' => 'NA',
                            'first_name' => auth()->user()->name, 'street' => 'NA', 'building' => 'NA',
                            'phone_number' => 'NA', 'shipping_method' => 'NA', 'postal_code' => 'NA',
                            'city' => 'Cairo', 'country' => 'EG', 'last_name' => auth()->user()->name, 'state' => 'Cairo'
                        ];

                        $res = $service->createPayment([
                            'amount' => $amountCents,
                            'currency' => 'EGP',
                            'items' => $paymentData['items'],
                            'order_id' => $paymentData['order_id'],
                            'billing_data' => $billing,
                        ]);

                        // Store payment locally
                        Payment::create([
                            'user_id' => auth()->id(),
                            'cv_id' => $record->id,
                            'order_id' => (string)($res['id'] ?? null),
                            'amount' => $amountCents / 100,
                            'currency' => 'EGP',
                            'status' => 'pending',
                            'paymob_data' => $res,
                        ]);

                        $iframeUrl = $service->getIframeUrl($res['payment_key']);

                        return redirect()->away($iframeUrl);
                    }),
                Action::make('download_pdf')
                    ->label('Download PDF')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->visible(fn (Cv $record): bool => (bool) $record->is_paid && $record->pdf_path)
                    ->url(fn (Cv $record): string => asset('storage/' . $record->pdf_path))
                    ->openUrlInNewTab(),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn (Cv $record): bool => $record->status !== 'paid'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCvs::route('/'),
            'create' => Pages\CreateCv::route('/create'),
            'edit' => Pages\EditCv::route('/{record}/edit'),
            // Register the view page so /user/cvs/{record} resolves to the Filament View page
            'view' => Pages\ViewCv::route('/{record}'),
        ];
    }
}
