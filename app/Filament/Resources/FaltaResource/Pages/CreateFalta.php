<?php

namespace App\Filament\Resources\FaltaResource\Pages;

use App\Filament\Resources\FaltaResource;
use App\Models\Aula;
use App\Models\CategoryNilai;
use App\Models\ClassDisciplina;
use App\Models\Classroom;
use App\Models\classroomHasSubject;
use App\Models\Falta;
use App\Models\Periode;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Term;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Support\Facades\Auth;


class CreateFalta extends CreateRecord
{
    protected static string $resource = FaltaResource::class;
    protected static string $view = 'filament.resources.falta-resource.pages.falta-nota';

    public function form(Form $form): Form
    {
        return $form->schema( [
            Card::make()
           ->schema([
            Card::make()
            ->schema( fn (Get $get): array =>[

                Select::make('classrooms')
                ->options(function () {
                    $user = Auth::user();  // Pega o usuário logado
                    if ($user->hasRole('professor')) {  // Verifica se o usuário tem o papel de professor
                        $teacherId = $user->teacher->id;  // Pega o ID do professor logado
                        $gradeIds = ClassDisciplina::where('teachers_id', $teacherId)  // Filtra class_disciplina pelo ID do professor
                        ->pluck('grade_id');  // Obtém os IDs das classes
                       // dd($gradeIds);
                    // Obter as turmas (classrooms) que estão associadas a essas classes
                    return Classroom::whereIn('grades_id', $gradeIds)  // Obtém as salas de aula que correspondem aos IDs filtrados
                        ->pluck('name', 'id');  // Prepara a lista de opções para o Select
                    } else {
                        // Se não é professor, pode retornar todas as salas de aula ou uma lista vazia
                        return Classroom::pluck('name', 'id');  // Retorna todas as salas de aula
                        // ou
                        // return [];  // Retorna uma lista vazia
                    }
                })
                ->label('Turma')
                ->required()
                ->live()
                ->afterStateUpdated(function (Set $set){
                   $set('aulas', null);
                   $set('student', null);
                     }),

                Select::make('aulas')
                ->options(Aula::where('classrooms_id',$get('classrooms'))->pluck('sumario','id') )
                ->label('Aula')
                ->required()
                ->live()

            ])->columns(2),


            Repeater::make('nilaistudents')
            ->label("Grade")
            ->schema( fn (Get $get): array =>  [
                Select::make('student')
                ->options( function  () use ($get) {
                    $data = Student::whereIn('id', function ($query) use ($get){
                        $query->select('students_id')
                        ->from('student_has_classes')
                        ->where('classrooms_id', $get('classrooms'))
                        ->pluck('students_id');
                    })
                    ->pluck('name', 'id');
                    return $data;
                })
                ->label('Estudante')
                ->required(),
                Checkbox::make('falta')
                ->label('falta')
                ->required()

            ])->columns(2)
           ])
            ]);
    }

    public function save(){
                $get=$this->form->getState();
                $get['aulas'];

                $insert = [];
                //dd($get['aulas']);
        // Itera sobre cada estudante listado no repeater 'nilaistudents'
                foreach ($get['nilaistudents'] as $row) {
            // Adiciona ao array de inserção cada registro de falta para os estudantes

            array_push($insert, [
                'aulas_id' => $get['aulas'],  // ID da aula selecionada
                'students_id' => $row['student'],  // ID do estudante
                'falta' => $row['falta'],  // Estado da falta (presença/ausência)
            ]);
        }

        // Insere todos os dados coletados na tabela 'Falta'
        Falta::insert($insert);

        // Redireciona para a página de visualização de faltas após a inserção
        return redirect()->to('admin/faltas');
    }
}
