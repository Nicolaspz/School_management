<?php

namespace App\Filament\Resources\ClassroomHasSubjectResource\Pages;

use App\Filament\Resources\ClassroomHasSubjectResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListClassroomHasSubjects extends ListRecords
{
    protected static string $resource = ClassroomHasSubjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
