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
}
