<?php

namespace App\Filament\Resources\ClassroomHasSubjectResource\Pages;

use App\Filament\Resources\ClassroomHasSubjectResource;
use App\Models\Classroom;
use App\Models\classroomHasSubject;
use App\Models\Curso;
use App\Models\grade;
use App\Models\Periode;
use App\Models\Subject;
use App\Models\Teacher;
use Filament\Actions;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Pages\CreateRecord;

class CreateClassroomHasSubject extends CreateRecord
{
    protected static string $resource = ClassroomHasSubjectResource::class;
    protected static string $view = 'filament.resources.classubject-resource.pages.create_classsubject';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                    Card::make("Dados Académicos")
                    ->schema(fn (Get $get): array =>[
                        Select::make('teachers_id')
                        ->options(Teacher::all()->pluck('name','id'))
                        ->label('Professor')
                        ->required(),
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
                            $set('subject_id', null);
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
                            Select::make('subject_id')
                            ->options(function (Get $get) {
                                // Pega a classe selecionada
                                $gradeId = $get('grade');
                                // Pega a turma selecionada
                                $classroomId = $get('classrooms');

                                if ($gradeId && $classroomId) {
                                    return Subject::whereHas('grades', function ($query) use ($gradeId) {
                                        $query->where('grade_id', $gradeId);
                                    })
                                    ->whereDoesntHave('classrooms', function ($query) use ($classroomId) {
                                        $query->where('classroom_id', $classroomId);
                                    })
                                    ->pluck('name', 'id');
                                }
                                return collect();
                            })
                            ->label('Disciplina')
                            ->searchable()
                            ->live()
                            ->required()
                            ->preload(),

                ])->columns(2)
            ]);
    }

    public function save(){
        $get=$this->form->getState();
        classroomHasSubject::create([
            'subject_id'=> $get['subject_id'],
            'teachers_id'=>$get['teachers_id'],
            'classroom_id'=> $get['classrooms'],
        ]);
        return redirect()->to('admin/classroom-has-subjects');
        }
}
