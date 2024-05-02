<?php

namespace App\Filament\Resources\ClassDisciplinaResource\Pages;

use App\Filament\Resources\ClassDisciplinaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListClassDisciplinas extends ListRecords
{
    protected static string $resource = ClassDisciplinaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
