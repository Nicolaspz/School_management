<?php

namespace App\Filament\Resources\ConsultaResource\Pages;

use App\Filament\Resources\ConsultaResource;
use Filament\Actions;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;

class ListConsultas extends ListRecords
{
    protected static string $resource = ConsultaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'Todas' => Tab::make(),
            'Aceite' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'Aceite')),
            'Pendente' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'Pendente')),
                'consultado' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'consultado')),
        ];
    }
}
