<?php

namespace App\Filament\Resources\NotaResource\Pages;

use App\Filament\Resources\NotaResource;
use App\Models\CategoryNilai;
use App\Models\Classroom;
use App\Models\classroomHasSubject;
use App\Models\Nota;
use App\Models\Periode;
use App\Models\Student;
use App\Models\Subject;
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
            ->schema([
                Select::make('classrooms')
            ->options(Classroom::all()->pluck('name','id'))
            ->label('Class')
            ->live()
            ->afterStateUpdated(function (Set $set){
                $set('student', null);
                $set('periode', null);
                $set('subject', null);

            }),
            Select::make('periode')
            ->searchable()
            ->options(Periode::all()->pluck('name','id'))
            ->label('Periodo')
            ->live()
            ->preload()
            ->afterStateUpdated(fn(Set $set)=> $set('student', null)),
            Card::make()
            ->schema(fn (Get $get): array =>[
                Select::make('subject_id')
                ->options(function () use ($get) {
                    $subjectIds = classroomHasSubject::where('classroom_id', $get('classrooms'))->pluck('subject_id');
                    $subjects = Subject::whereIn('id', $subjectIds)->pluck('name', 'id');
                    return $subjects;
                })
                ->label('Disciplina'),
            ]),

            Select::make('category_nilai')
            ->options(CategoryNilai::all()->pluck('name','id'))
            ->label('categoria da nota')
            ->columnSpan(3)
            ])->columns(3),


            Repeater::make('nilaistudents')
            ->label("Grade")
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
                ->label('Estudante'),
                TextInput::make('nota')
                ->label('Nota')

            ])->columns(2)
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
                'periode_id'=> $get['periode'],
                'teacher_id'=>Auth::user()->id,
                'subject_id'=> $get['subject_id'],
                'category_notas_id'=> $get['category_nilai'],
                'nota'=> $row['nota'],
            ]);

        }
        Nota::insert($insert);
        return redirect()->to('admin/notas');
    }
}
