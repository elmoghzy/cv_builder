<?php

namespace App\Filament\User\Resources\CvResource\Pages;

use App\Filament\User\Resources\CvResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions;
use Illuminate\Database\Eloquent\Builder;

class ListCvs extends ListRecords
{
    protected static string $resource = CvResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
