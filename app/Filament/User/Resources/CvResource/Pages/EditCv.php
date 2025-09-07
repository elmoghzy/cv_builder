<?php

namespace App\Filament\User\Resources\CvResource\Pages;

use App\Filament\User\Resources\CvResource;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Builder;

class EditCv extends EditRecord
{
    protected static string $resource = CvResource::class;

    protected function getRedirectUrl(): string
    {
    return $this->getResource()::getUrl('view', ['record' => $this->record]);
    }
}
