<?php

namespace App\Filament\Widgets;

namespace App\Filament\Widgets;

use App\Models\Grade; // Substitua pela localização correta do seu modelo, se necessário
use App\Models\Nota;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class SubjectGradesChart extends ChartWidget
{
    protected static ?string $heading = 'Nota Média por Matéria';



    protected function getData(): array {
        $data = Nota::query()
            ->join('subjects', 'notas.subject_id', '=', 'subjects.id')
            ->select('subjects.name', DB::raw('AVG(notas.p1) as average_p1'))
            ->groupBy('subjects.name')
            ->orderBy('notas.p1', 'desc')
            ->get();

        $labels = $data->pluck('name')->toArray();
        $values = $data->pluck('average_p1')->toArray();

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Nota média P1',
                    'data' => $values,
                    'backgroundColor' => '#4ade80',
                    'borderColor' => '#166534',
                    'borderWidth' => 1,
                ]
            ]
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}

