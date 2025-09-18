<?php

namespace App\Filament\Components;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Actions\Action;

class AIEnhancedTextarea extends Textarea
{
    protected function setUp(): void
    {
        parent::setUp();
        
        // Add AI enhancement button
        $this->hintAction(
            Action::make('ai-enhance')
                ->icon('heroicon-m-sparkles')
                ->tooltip('AI Enhancement')
                ->color('primary')
                ->action(function ($state, $set, $get, $component) {
                    $fieldName = $component->getName();
                    
                    // Different AI prompts based on field name
                    switch ($fieldName) {
                        case 'professional_summary':
                            $set($fieldName, 'Experienced professional with strong background in software development and team leadership. Proven track record of delivering high-quality solutions.');
                            break;
                        case 'objective':
                            $set($fieldName, 'Seeking a challenging position where I can utilize my skills and experience to contribute to company growth while advancing my career.');
                            break;
                        case 'description':
                            $set($fieldName, 'Responsible for developing and maintaining software applications, collaborating with cross-functional teams, and ensuring code quality.');
                            break;
                        default:
                            // Generic AI enhancement
                            $set($fieldName, 'AI-generated content for ' . $fieldName);
                            break;
                    }
                })
        );
    }
}
