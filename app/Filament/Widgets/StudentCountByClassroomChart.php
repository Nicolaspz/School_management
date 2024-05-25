<?php

namespace App\Filament\Widgets;

use App\Models\Student;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class StudentCountByClassroomChart extends ChartWidget
{
    protected static ?string $heading = 'Quantidade de Estudantes por Turma';

    protected function getData(): array {
        $data = DB::table('student_has_classes')
            ->join('classrooms', 'student_has_classes.classrooms_id', '=', 'classrooms.id')
            ->select('classrooms.name as classroom_name', DB::raw('COUNT(student_has_classes.students_id) as student_count'))
            ->where('student_has_classes.is_open', 1)  // Considera apenas as classes que estão abertas
            ->groupBy('classrooms.id', 'classrooms.name')  // Adiciona 'classrooms.name' ao GROUP BY
            ->orderBy('student_count', 'desc')
            ->get();

        $labels = $data->pluck('classroom_name')->toArray();
        $values = $data->pluck('student_count')->toArray();

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Quantidade de Estudantes',
                    'data' => $values,
                    'backgroundColor' => '#93c5fd',
                    'borderColor' => '#1e40af',
                    'borderWidth' => 1,
                ]
            ]
        ];
    }

    protected function getType(): string
    {
        return 'bar'; // Gráfico de barras
    }
}
