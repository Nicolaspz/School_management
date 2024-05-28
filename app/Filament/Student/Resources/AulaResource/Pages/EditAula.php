<?php

namespace App\Filament\Student\Resources\AulaResource\Pages;

use App\Filament\Student\Resources\AulaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAula extends EditRecord
{
    protected static string $resource = AulaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
