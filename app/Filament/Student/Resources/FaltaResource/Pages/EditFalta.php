<?php

namespace App\Filament\Student\Resources\FaltaResource\Pages;

use App\Filament\Student\Resources\FaltaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFalta extends EditRecord
{
    protected static string $resource = FaltaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
