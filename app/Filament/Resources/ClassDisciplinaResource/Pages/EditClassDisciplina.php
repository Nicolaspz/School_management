<?php

namespace App\Filament\Resources\ClassDisciplinaResource\Pages;

use App\Filament\Resources\ClassDisciplinaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditClassDisciplina extends EditRecord
{
    protected static string $resource = ClassDisciplinaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
