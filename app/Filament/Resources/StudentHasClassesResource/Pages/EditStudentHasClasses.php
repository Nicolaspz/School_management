<?php

namespace App\Filament\Resources\StudentHasClassesResource\Pages;

use App\Filament\Resources\StudentHasClassesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStudentHasClasses extends EditRecord
{
    protected static string $resource = StudentHasClassesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
