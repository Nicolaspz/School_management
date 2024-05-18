<?php

namespace App\Filament\Resources\StudentHasClassesResource\Pages;

use App\Filament\Resources\StudentHasClassesResource;
use App\Models\Classroom;
use App\Models\Curso;
use App\Models\grade;
use App\Models\Periode;
use App\Models\Student;
use App\Models\StudentHasClasses;
use Filament\Actions;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Pages\CreateRecord;
use Filament\Forms\Form;

class CreateStudentHasClasses extends CreateRecord
{
    protected static string $resource = StudentHasClassesResource::class;
    protected static string $view = 'filament.resources.student-has-classes-resource.pages.form-student-class';

    public function form(Form $form): Form
    {
        return $form
            ->schema([

                Card::make("Dados Académicos")
                ->schema(fn (Get $get): array =>[
                    Select::make('curso')
                    ->options(Curso::all()->pluck('name','id'))
                    ->label('Curso')
                    ->required()
                    ->live()
                    ->afterStateUpdated(function (Set $set){
                        $set('student', null);
                        $set('periode', null);
                        $set('subject_id', null);
                        $set('grade', null);

                    }),


                    Select::make('grade')
                    ->options(grade::where('cursos_id',$get('curso'))->pluck('name','id'))
                    ->label('classe')
                    ->required()
                    ->live()
                    ->afterStateUpdated(function (Set $set){
                        $set('classrooms', null);
                     }),

                    Select::make('classrooms')
                    ->options(Classroom::where('grade_id',$get('grade'))->pluck('name','id'))
                    ->label('Turma')
                    ->required()
                    ->live(),
                    Select::make('periode')
                        ->searchable()
                        ->options(Periode::all()->pluck('name','id'))
                        ->label('Ano Lectivo')
                        ->live()
                        ->required()
                        ->preload(),
                        Select::make('students')
                        ->searchable()
                        ->multiple()
                        ->options(
                            Student::whereNotIn('id', function($query) {
                                $query->select('students_id')
                                ->from('student_has_classes');
                            })->pluck('name', 'id')
                        )
                        ->columnSpan(3)
                        ->label('Estudante'),

                ])->columns(2)
            ]);

    }

    public function save()
{
    $formState = $this->form->getState();  // Obter o estado do formulário

    $students = $formState['students'];
    $classrooms = $formState['classrooms'];
    $periodes = $formState['periode'];

    $insert = [];
    foreach ($students as $student) {
        array_push($insert, [
            'students_id' => $student,
            'classrooms_id' => $classrooms,
            'periodes_id' => $periodes,
            'is_open' => 1
        ]);
    }

    StudentHasClasses::insert($insert);
    return redirect('admin/student-has-classes');
}
}
