<?php

namespace App\Filament\User\Resources;

use App\Filament\User\Resources\CvResource\Pages;
use App\Filament\Components\AIEnhancedTextInput;
use App\Filament\Components\AIEnhancedTextarea;
use App\Models\Cv;
use App\Models\Payment;
use App\Models\Template;
use App\Services\PayMobService;
use Filament\Forms;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
class CvResource extends Resource
{
    protected static ?string $model = Cv::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $modelLabel = 'My CV';
    protected static ?string $pluralModelLabel = 'My CVs';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('user_id', auth()->id());
    }

    public static function form(Form $form): Form
    {
        return $form
            ->columns([
                'sm' => 1,
                'md' => 2, 
                'lg' => 3,
                'xl' => 4,
            ])
            ->schema([
                Wizard::make([
                    Wizard\Step::make('Goal')
                        ->description('Tell us about yourself and your career goals')
                        ->icon('heroicon-o-light-bulb')
                        ->schema([
                            Forms\Components\Section::make('About You')
                                ->description('Help us understand your background to create the perfect CV')
                                ->schema([
                                    Select::make('persona')
                                        ->label('What stage are you at in your career?')
                                        ->options([
                                            'student' => '🎓 Student / Intern',
                                            'graduate' => '🌟 Fresh Graduate',
                                            'experienced' => '💼 Experienced Professional',
                                            'senior' => '👔 Senior Professional',
                                        ])
                                        ->default('graduate')
                                        ->dehydrated(false)
                                        ->live()
                                        ->native(false),
                                        
                                    TextInput::make('target_role')
                                        ->label('What role are you targeting?')
                                        ->placeholder('e.g. Software Engineer, Marketing Manager, Data Analyst')
                                        ->maxLength(120)
                                        ->dehydrated(false)
                                        ->reactive()
                                        ->afterStateUpdated(function ($state, $set, $get) {
                                            if ($state && empty($get('title'))) {
                                                $set('title', 'CV - ' . $state);
                                            }
                                        })
                                        ->columnSpan(2),
                                ])->columns(3),
                                
                            Forms\Components\Section::make('CV Preferences')
                                ->description('Customize how your CV will be created')
                                ->schema([
                                    Toggle::make('simple_mode')
                                        ->label('🚀 Quick & Simple Mode')
                                        ->default(true)
                                        ->dehydrated(false)
                                        ->live()
                                        ->helperText('Perfect for getting started quickly. Creates a professional CV with essential sections only.')
                                        ->columnSpan(1),
                                        
                                    Toggle::make('quick_fill')
                                        ->label('✨ AI-Powered Quick Fill')
                                        ->dehydrated(false)
                                        ->live()
                                        ->helperText('Let AI automatically fill your CV with professional content based on your profile and target role.')
                                        ->columnSpan(1)
                                        ->afterStateUpdated(function ($state, $set, $get) {
                                            if (!$state) {
                                                return;
                                            }

                                            $user = auth()->user();
                                            $persona = $get('persona') ?: 'graduate';
                                            $role = $get('target_role') ?: 'Software Engineer';

                                            try {
                                                $ai = app(\App\Services\AiCvContentService::class);
                                                $generated = $ai->generate([
                                                    'persona' => $persona,
                                                    'target_role' => $role,
                                                    'language' => 'en',
                                                    'user' => [
                                                        'name' => $user->name ?? null,
                                                        'email' => $user->email ?? null,
                                                    ],
                                                ]);

                                                $set('content.personal_info', [
                                                    'full_name' => $user->name ?? 'Full Name',
                                                    'email' => $user->email ?? 'example@email.com',
                                                    'phone' => $user->phone ?? '+20 123 456 7890',
                                                    'address' => 'Cairo, Egypt',
                                                    'linkedin' => 'https://linkedin.com/in/username',
                                                    'website' => 'https://portfolio.example.com',
                                                ]);

                                                if (!empty($generated['professional_summary'])) {
                                                    $set('content.professional_summary', $generated['professional_summary']);
                                                }
                                                if (!empty($generated['work_experience'])) {
                                                    $set('content.work_experience', $generated['work_experience']);
                                                }
                                                if (!empty($generated['education'])) {
                                                    $set('content.education', $generated['education']);
                                                }
                                                if (!empty($generated['technical_skills'])) {
                                                    $set('content.technical_skills', $generated['technical_skills']);
                                                }
                                                if (!empty($generated['soft_skills'])) {
                                                    $set('content.soft_skills', $generated['soft_skills']);
                                                }
                                                if (!empty($generated['languages'])) {
                                                    $set('content.languages', $generated['languages']);
                                                }
                                            } catch (\Exception $e) {
                                                // Handle AI service errors gracefully
                                            }
                                        }),
                                ])->columns(2),
                        ]),

                    Wizard\Step::make('Basics')
                        ->description('Choose your CV template and basic settings')
                        ->icon('heroicon-o-cog-6-tooth')
                        ->schema([
                            Forms\Components\Section::make('CV Details')
                                ->schema([
                                    TextInput::make('title')
                                        ->label('CV Title')
                                        ->placeholder('Give your CV a descriptive title')
                                        ->required()
                                        ->maxLength(255)
                                        ->columnSpan(2),
                                        
                                    Select::make('template_id')
                                        ->relationship('template', 'name')
                                        ->label('Choose Template')
                                        ->required()
                                        ->preload()
                                        ->searchable()
                                        ->native(false)
                                        ->default(fn () => Template::active()->ordered()->value('id'))
                                        ->helperText('Select a professional template that matches your style')
                                        ->placeholder('Select a template...')
                                        ->columnSpan(1),
                                        
                                    ColorPicker::make('accent_color')
                                        ->label('Accent Color')
                                        ->helperText('Choose a color to personalize your CV template')
                                        ->default('#3B82F6')
                                        ->columnSpan(1),
                                ])->columns(4),
                        ]),

                    Wizard\Step::make('Personal Information')
                        ->description('Your contact details and basic information')
                        ->icon('heroicon-o-user')
                    ->schema([
                        Forms\Components\Section::make('Contact Information')
                            ->description('How employers can reach you')
                            ->schema([
                                Group::make()->statePath('content.personal_info')->schema([
                                    AIEnhancedTextInput::make('full_name')
                                        ->label('Full Name')
                                        ->required()
                                        ->placeholder('Enter your full name')
                                        ->maxLength(120),
                                        
                                    AIEnhancedTextInput::make('email')
                                        ->label('Email Address')
                                        ->email()
                                        ->required()
                                        ->placeholder('your.email@example.com')
                                        ->maxLength(150),
                                        
                                    AIEnhancedTextInput::make('phone')
                                        ->label('Phone Number')
                                        ->tel()
                                        ->placeholder('+20 123 456 7890')
                                        ->maxLength(50),
                                        
                                    AIEnhancedTextInput::make('address')
                                        ->label('Address')
                                        ->placeholder('City, Country')
                                        ->maxLength(200),
                                        
                                    AIEnhancedTextInput::make('linkedin')
                                        ->label('LinkedIn Profile')
                                        ->url()
                                        ->placeholder('https://linkedin.com/in/yourprofile')
                                        ->maxLength(200),
                                        
                                    AIEnhancedTextInput::make('website')
                                        ->label('Website/Portfolio')
                                        ->url()
                                        ->placeholder('https://yourportfolio.com')
                                        ->maxLength(200),
                                ])->columns(3),
                            ]),
                    ]),

                Wizard\Step::make('Professional Summary')
                    ->description('Highlight your key strengths and career goals')
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        Forms\Components\Section::make('About You')
                            ->description('Write a compelling summary that grabs attention')
                            ->schema([
                                Group::make()->statePath('content')->schema([
                                    AIEnhancedTextarea::make('professional_summary')
                                        ->label('Professional Summary')
                                        ->placeholder('Write a brief summary highlighting your key skills, experience, and what you bring to the role...')
                                        ->rows(5)
                                        ->maxLength(1000)
                                        ->helperText('2-4 sentences that showcase your value proposition')
                                        ->columnSpan(1),
                                        
                                    AIEnhancedTextarea::make('objective')
                                        ->label('Career Objective (Optional)')
                                        ->placeholder('State your career goals and what you hope to achieve in your next role...')
                                        ->rows(5)
                                        ->maxLength(500)
                                        ->helperText('Focus on what you want to achieve and how you can contribute')
                                        ->columnSpan(1),
                                ])->columns(2),
                            ]),
                    ]),

                Wizard\Step::make('Work Experience')
                    ->description('Your professional work history')
                    ->icon('heroicon-o-briefcase')
                    ->schema([
                        Forms\Components\Section::make('Professional Experience')
                            ->description('List your work experience, starting with the most recent')
                            ->schema([
                                Repeater::make('work_experience')
                                    ->statePath('content.work_experience')
                                    ->label('')
                                    ->schema([
                                        AIEnhancedTextInput::make('job_title')
                                            ->label('Job Title')
                                            ->required()
                                            ->placeholder('e.g. Software Engineer')
                                            ->maxLength(120),
                                            
                                        AIEnhancedTextInput::make('company')
                                            ->label('Company')
                                            ->required()
                                            ->placeholder('e.g. Tech Solutions Inc.')
                                            ->maxLength(120),
                                            
                                        AIEnhancedTextInput::make('location')
                                            ->label('Location')
                                            ->placeholder('e.g. Cairo, Egypt')
                                            ->maxLength(120),
                                            
                                        DatePicker::make('start_date')
                                            ->label('Start Date')
                                            ->native(false)
                                            ->required(),
                                            
                                        DatePicker::make('end_date')
                                            ->label('End Date')
                                            ->native(false)
                                            ->hidden(fn ($get) => (bool) $get('current')),
                                            
                                        Toggle::make('current')
                                            ->label('Currently working here')
                                            ->live()
                                            ->inline(false)
                                            ->default(false),
                                            
                                        AIEnhancedTextarea::make('description')
                                            ->label('Job Description')
                                            ->placeholder('Describe your role, responsibilities, and key contributions...')
                                            ->rows(4)
                                            ->columnSpanFull()
                                            ->helperText('Focus on your achievements and impact'),
                                            
                                        AIEnhancedTextarea::make('achievements')
                                            ->label('Key Achievements')
                                            ->placeholder('• Increased sales by 25%\n• Led a team of 5 developers\n• Implemented new process that saved 20 hours/week')
                                            ->rows(3)
                                            ->columnSpanFull()
                                            ->helperText('Use bullet points to highlight specific accomplishments'),
                                    ])
                                    ->columns(3)
                                    ->addActionLabel('+ Add Work Experience')
                                    ->itemLabel(fn (array $state): ?string => 
                                        ($state['job_title'] ?? 'New Position') . 
                                        (isset($state['company']) ? ' at ' . $state['company'] : '')
                                    )
                                    ->collapsible()
                                    ->cloneable(),
                            ]),
                    ])
                    ->visible(fn ($get) => in_array(($get('persona') ?? 'graduate'), ['experienced', 'senior']) || !($get('simple_mode') ?? true)),

                Wizard\Step::make('Education')
                    ->description('Your educational background')
                    ->icon('heroicon-o-academic-cap')
                    ->schema([
                        Forms\Components\Section::make('Educational History')
                            ->description('List your educational qualifications')
                            ->schema([
                                Repeater::make('education')
                                    ->statePath('content.education')
                                    ->label('')
                                    ->schema([
                                        AIEnhancedTextInput::make('degree')
                                            ->label('Degree/Qualification')
                                            ->required()
                                            ->placeholder('e.g. Bachelor of Computer Science')
                                            ->maxLength(120),
                                            
                                        AIEnhancedTextInput::make('institution')
                                            ->label('Institution')
                                            ->required()
                                            ->placeholder('e.g. Cairo University')
                                            ->maxLength(120),
                                            
                                        AIEnhancedTextInput::make('location')
                                            ->label('Location')
                                            ->placeholder('e.g. Cairo, Egypt')
                                            ->maxLength(120),
                                            
                                        DatePicker::make('graduation_date')
                                            ->label('Graduation Date')
                                            ->native(false),
                                            
                                        TextInput::make('gpa')
                                            ->label('GPA (Optional)')
                                            ->placeholder('e.g. 3.8/4.0')
                                            ->maxLength(20),
                                            
                                        AIEnhancedTextarea::make('honors')
                                            ->label('Honors & Achievements')
                                            ->placeholder('Dean\'s List, Magna Cum Laude, relevant coursework...')
                                            ->rows(2)
                                            ->columnSpanFull(),
                                    ])
                                    ->columns(3)
                                    ->addActionLabel('+ Add Education')
                                    ->itemLabel(fn (array $state): ?string => 
                                        ($state['degree'] ?? 'New Education') . 
                                        (isset($state['institution']) ? ' - ' . $state['institution'] : '')
                                    )
                                    ->collapsible()
                                    ->defaultItems(1),
                            ]),
                    ]),

                Wizard\Step::make('Skills & Abilities')
                    ->description('Showcase your technical and soft skills')
                    ->icon('heroicon-o-star')
                    ->schema([
                        Forms\Components\Section::make('Skills')
                            ->description('List your key skills and competencies')
                            ->schema([
                                Group::make()->statePath('content')->schema([
                                    Repeater::make('technical_skills')
                                        ->label('Technical Skills')
                                        ->schema([
                                            TextInput::make('skill')
                                                ->label('Skill')
                                                ->required()
                                                ->placeholder('e.g. JavaScript, Python, Project Management')
                                                ->maxLength(100),
                                            Radio::make('level')
                                                ->label('Proficiency Level')
                                                ->options([
                                                    '1' => '★☆☆☆☆ Beginner',
                                                    '2' => '★★☆☆☆ Basic',
                                                    '3' => '★★★☆☆ Intermediate',
                                                    '4' => '★★★★☆ Advanced',
                                                    '5' => '★★★★★ Expert',
                                                ])
                                                ->default('3')
                                                ->required()
                                                ->inline(),
                                        ])
                                        ->columns(2)
                                        ->reorderable()
                                        ->collapsible()
                                        ->defaultItems(1)
                                        ->addActionLabel('Add Technical Skill')
                                        ->columnSpan(2),
                                        
                                    Repeater::make('soft_skills')
                                        ->label('Soft Skills')
                                        ->schema([
                                            TextInput::make('skill')
                                                ->label('Skill')
                                                ->required()
                                                ->placeholder('e.g. Leadership, Communication, Problem Solving')
                                                ->maxLength(100),
                                            Radio::make('level')
                                                ->label('Proficiency Level')
                                                ->options([
                                                    '1' => '★☆☆☆☆ Beginner',
                                                    '2' => '★★☆☆☆ Basic',
                                                    '3' => '★★★☆☆ Intermediate',
                                                    '4' => '★★★★☆ Advanced',
                                                    '5' => '★★★★★ Expert',
                                                ])
                                                ->default('3')
                                                ->required()
                                                ->inline(),
                                        ])
                                        ->columns(2)
                                        ->reorderable()
                                        ->collapsible()
                                        ->defaultItems(1)
                                        ->addActionLabel('Add Soft Skill')
                                        ->columnSpan(2),
                                        
                                    TagsInput::make('languages')
                                        ->label('Languages')
                                        ->placeholder('Add languages...')
                                        ->separator(',')
                                        ->helperText('e.g. English (Fluent), Arabic (Native), French (Intermediate)')
                                        ->columnSpan(4),
                                ])->columns(4),
                            ]),
                    ]),

                Wizard\Step::make('Additional Information')
                    ->description('Projects, certifications, and other achievements')
                    ->icon('heroicon-o-trophy')
                    ->schema([
                        Forms\Components\Section::make('Projects')
                            ->description('Showcase your notable projects')
                            ->schema([
                                Repeater::make('projects')
                                    ->statePath('content.projects')
                                    ->label('')
                                    ->schema([
                                        TextInput::make('project_name')
                                            ->label('Project Name')
                                            ->required()
                                            ->placeholder('e.g. E-commerce Website')
                                            ->maxLength(150),
                                            
                                        Textarea::make('description')
                                            ->label('Description')
                                            ->placeholder('Describe what the project does and your role in it...')
                                            ->rows(3)
                                            ->columnSpanFull(),
                                            
                                        TextInput::make('technologies')
                                            ->label('Technologies Used')
                                            ->placeholder('e.g. React, Node.js, MongoDB')
                                            ->helperText('Separate multiple technologies with commas')
                                            ->maxLength(200),
                                            
                                        TextInput::make('duration')
                                            ->label('Duration')
                                            ->placeholder('e.g. 3 months, Jan 2024 - Mar 2024')
                                            ->maxLength(100),
                                            
                                        TextInput::make('url')
                                            ->label('Project URL (Optional)')
                                            ->url()
                                            ->placeholder('https://github.com/username/project')
                                            ->maxLength(200),
                                    ])
                                    ->columns(2)
                                    ->addActionLabel('+ Add Project')
                                    ->itemLabel(fn (array $state): ?string => $state['project_name'] ?? 'New Project')
                                    ->collapsible(),
                            ])->collapsible(),
                            
                        Forms\Components\Section::make('Certifications')
                            ->description('Professional certifications and credentials')
                            ->schema([
                                Repeater::make('certifications')
                                    ->statePath('content.certifications')
                                    ->label('')
                                    ->schema([
                                        TextInput::make('name')
                                            ->label('Certification Name')
                                            ->required()
                                            ->placeholder('e.g. AWS Certified Developer')
                                            ->maxLength(150),
                                            
                                        TextInput::make('issuer')
                                            ->label('Issuing Organization')
                                            ->placeholder('e.g. Amazon Web Services')
                                            ->maxLength(150),
                                            
                                        DatePicker::make('date')
                                            ->label('Date Obtained')
                                            ->native(false),
                                            
                                        TextInput::make('credential_id')
                                            ->label('Credential ID (Optional)')
                                            ->placeholder('Certificate verification number')
                                            ->maxLength(120),
                                    ])
                                    ->columns(2)
                                    ->addActionLabel('+ Add Certification')
                                    ->itemLabel(fn (array $state): ?string => 
                                        ($state['name'] ?? 'New Certification') . 
                                        (isset($state['issuer']) ? ' - ' . $state['issuer'] : '')
                                    )
                                    ->collapsible(),
                            ])->collapsible(),
                    ])
                    ->visible(fn ($get) => !($get('simple_mode') ?? true) || in_array(($get('persona') ?? 'graduate'), ['student', 'graduate', 'senior'])),
            ])
            ->skippable()
            ->persistStepInQueryString()
            ->startOnStep(1)
            ->columnSpanFull(),
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
                        $amountCents = (int) (config('paymob.cv_price_cents', 5000));
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
            'view' => Pages\ViewCv::route('/{record}'),
        ];
    }
}
