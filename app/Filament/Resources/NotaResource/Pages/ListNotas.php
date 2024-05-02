<?php

namespace App\Filament\Resources\NotaResource\Pages;

use App\Filament\Resources\NotaResource;
use App\Models\Term;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListNotas extends ListRecords
{
    protected static string $resource = NotaResource::class;
    //protected static string $view = 'filament.resources.nota-resource.pages.list_nota';


    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
{
    $tabs = [];

    $trimestre = Term::orderBy('name')->get();

    foreach ($trimestre as $classroom) {
        $tabs[$classroom->name] = Tab::make($classroom->name)
            ->modifyQueryUsing(function (Builder $query) use ($classroom) {
                return $query->where('terms_id', $classroom->id);//->where('is_open', true);
            });
    }

    return $tabs;
}
}
