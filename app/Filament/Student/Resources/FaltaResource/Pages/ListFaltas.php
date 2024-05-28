<?php

namespace App\Filament\Student\Resources\FaltaResource\Pages;

use App\Filament\Student\Resources\FaltaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFaltas extends ListRecords
{
    protected static string $resource = FaltaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
