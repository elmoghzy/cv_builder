<?php

namespace App\Filament\User\Resources\CvResource\Pages;

use App\Filament\User\Resources\CvResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCv extends CreateRecord
{
    protected static string $resource = CvResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
        $data['status'] = $data['status'] ?? 'completed';
        return $data;
    }

    public function mount(): void
    {
        parent::mount();
        
        // Auto-fill personal information from authenticated user
        $user = auth()->user();
        if ($user) {
            $this->form->fill([
                'content' => [
                    'personal_info' => [
                        'full_name' => $user->name,
                        'email' => $user->email,
                        'phone' => $user->phone ?? '',
                        'address' => '',
                        'linkedin' => '',
                        'website' => '',
                    ]
                ]
            ]);
        }
    }

    /**
     * After creating a CV, take the user straight to the preview (view) page
     * so they can review then proceed to payment easily.
     */
    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('view', ['record' => $this->record]);
    }
}
