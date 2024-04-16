<?php

namespace App\Filament\Resources\StudentHasClassesResource\Pages;

use App\Filament\Resources\StudentHasClassesResource;
use App\Models\Classroom;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListStudentHasClasses extends ListRecords
{
    protected static string $resource = StudentHasClassesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
    public function getTabs(): array
{
    $tabs = [];

    $classrooms = Classroom::orderBy('name')->get();

    foreach ($classrooms as $classroom) {
        $tabs[$classroom->name] = Tab::make($classroom->name)
            ->modifyQueryUsing(function (Builder $query) use ($classroom) {
                return $query->where('classrooms_id', $classroom->id)->where('is_open', true);
            });
    }

    return $tabs;
}
}
