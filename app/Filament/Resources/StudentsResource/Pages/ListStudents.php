<?php

namespace App\Filament\Resources\StudentsResource\Pages;

use App\Filament\Resources\StudentsResource;
use App\Models\Student;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\View\View;

class ListStudents extends ListRecords
{
    protected static string $resource = StudentsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //Actions\CreateAction::make(),
        ];
    }

    public function getHeader(): ?View
    {
        $data=Actions\CreateAction::make();
        return view('filament.custom.upload-file',compact('data'));
    }

    public $file='';
    public function save(){
        Student::create([
            'nis'=>'23223',
            'name'=>'Nicolau Adão',
            'gender'=>'Male',
        ]);
    }
    public function getTabs(): array
{
    return [
        'all' => Tab::make(),
        'accept' => Tab::make()
            ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'accept')),
        'off' => Tab::make()
            ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'off')),
    ];
}
}
