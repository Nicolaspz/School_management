<?php

namespace App\Filament\Resources\NotaResource\Pages;

use App\Filament\Resources\NotaResource;
use App\Models\CategoryNilai;
use App\Models\ClassDisciplina;
use App\Models\Classroom;
use App\Models\classroomHasSubject;
use App\Models\Curso;
use App\Models\grade;
use App\Models\Nota;
use App\Models\Periode;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Term;
use Filament\Actions;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateNota extends CreateRecord
{
    protected static string $resource = NotaResource::class;
    protected static string $view = 'filament.resources.nota-resource.pages.form-nota';

    public function form(Form $form): Form
    {
        return $form->schema([
            Card::make()
           ->schema([
            Card::make()
            ->schema( fn (Get $get): array =>[
                Select::make('curso')
                ->options(function () {
                    $user = Auth::user();
                    if ($user->hasRole('professor')) {
                        $teacherId = $user->teacher->id;
                        $gradeIds = ClassDisciplina::where('teachers_id', $teacherId)
                        ->pluck('grade_id');
                    $curso= Classroom::whereIn('grade_id', $gradeIds)
                        ->pluck('cursos_id');
                        return Curso::whereIn('id', $curso)
                        ->pluck('name', 'id');

                    } else {
                        return Curso::pluck('name', 'id');

                    }
                })
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

            Select::make('term')
            ->options(Term::all()->pluck('name','id'))
            ->label('Trimestre')
            ->required(),
            Select::make('periode')
            ->searchable()
            ->options(Periode::all()->pluck('name','id'))
            ->label('Ano Lectivo')
            ->live()
            ->required()
            ->preload()
            ->afterStateUpdated(fn(Set $set)=> $set('student', null)),
            Card::make()
            ->schema(fn (Get $get): array => [
                Select::make('subject_id')
                ->options(function () use ($get) {
                    $user = Auth::user();
                    if ($user->hasRole('professor')) {
                        $data = Subject::whereIn('id', function ($query) use ($get){
                            $query->select('subject_id')
                            ->from('grade_subject')
                            ->where('grade_id', $get('grade'))
                            ->where('teachers_id', Auth::user()->teacher->id)
                            ->pluck('subject_id');
                        })
                        ->pluck('name', 'id');
                     }
                    else{
                        $data = Subject::whereIn('id', function ($query) use ($get){
                            $query->select('subject_id')
                            ->from('grade_subject')
                            ->where('grade_id', $get('grade'))
                            ->pluck('subject_id');
                        })
                        ->pluck('name', 'id');
                    }

                    return $data;

                     })
                ->label('Disciplina')
                ->required()
                ->live(),
        ]),

            ])->columns(3),


            Repeater::make('nilaistudents')
            ->label("Estudantes")
            ->schema( fn (Get $get): array =>  [
                Select::make('student')
                ->options( function  () use ($get) {
                    $data = Student::whereIn('id', function ($query) use ($get){
                        $query->select('students_id')
                        ->from('student_has_classes')
                        ->where('classrooms_id', $get('classrooms'))
                        ->where('periodes_id',$get('periode'))
                        ->where('is_open', true)->pluck('students_id');
                    })
                    ->pluck('name', 'id');
                    return $data;
                })
                ->label('Estudante')
                ->required(),
                TextInput::make('p1')
                ->label('P1'),
                TextInput::make('p2')
                ->label('P2'),
                TextInput::make('mac')
                ->label('Mac')


            ])->columns(4)
           ])
            ]);
    }

    public function save(){

        $get=$this->form->getState();
        $insert=[];
        foreach($get['nilaistudents'] as $row){
            array_push($insert,[
                'class_id' =>$get['classrooms'],
                'student_id'=> $row['student'],
                'periode_id'=> $get['periode'],//terms_id
                'terms_id'=> $get['term'],
                'teacher_id'=>Auth::user()->id,
                'subject_id'=> $get['subject_id'],
                'p1'=> $row['p1'],
                'p2'=> $row['p2'],
                'p2'=> $row['p2'],
                'mac'=>$row['mac'],
            ]);

        }

        Nota::insert($insert);
        return redirect()->to('admin/notas');
    }
}
