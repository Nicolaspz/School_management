<?php

namespace App\Filament\Resources\ConsultaResource\Widgets;

use App\Models\Classroom;
use App\Models\consulta;
use App\Models\Curso;
use App\Models\grade;
use App\Models\Student;
use App\Models\Teacher;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class ConsultaOverview extends BaseWidget
{


    protected function getStats(): array
    {
        $user = Auth::user();
        $role=$user->getRoleNames()[0];
         //dd($role=="super_admin");
        if ($role=="super_admin"){
            //dd("Passou");

            return [
                Stat::make('Consulta pendentes', consulta::query()->where('status', 'Pendente')->count()),
                Stat::make('Consulta Feitas', consulta::query()->where('status', 'consultado')->count()),
                Stat::make('Estudante', Student::query()->where('status', 'accept')->count()),
                Stat::make('Professor', Teacher::query()->count()),
                Stat::make('Turma', Classroom::query()->count()),
                Stat::make('Classe', grade::query()->count()),
                Stat::make('Curso', Curso::query()->count())
                ->color('danger'),
                ];  // Retorna um array vazio se o usuário não for um 'super-admin'.
        }

        return [];

    }
}
