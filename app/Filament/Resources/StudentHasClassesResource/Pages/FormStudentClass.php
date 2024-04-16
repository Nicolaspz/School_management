<?php

namespace App\Filament\Resources\StudentHasClassesResource\Pages;

use App\Filament\Resources\StudentHasClassesResource;
use App\Models\Classroom;
use App\Models\homerooms;
use App\Models\Periode;
use App\Models\Student;
use App\Models\StudentHasClasses;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Resources\Pages\Page;
use Psy\Readline\Hoa\Console;

use function Laravel\Prompts\alert;

class FormStudentClass extends Page implements HasForms
{
    use InteractsWithForms;
    protected static string $resource = StudentHasClassesResource::class;

    protected static string $view = 'filament.resources.student-has-classes-resource.pages.form-student-class';
    public $students=[];
    public $classroom='';
    public $periodes='';

    public function mount():void{
        $this->form->fill();
    }
    public function getFormSchema():array{
        return[
            Card::make([
                Select::make('students')
                ->searchable()
                ->multiple()
                ->options(Student::all()->pluck('name','id'))
                ->columnSpan(3)
                ->label('Estudante'),

                Select::make('classroom')
                ->searchable()
                ->options(Classroom::all()->pluck('name','id'))
                ->label('Classe/Turma'),
                Select::make('periodes')
                ->searchable()
                ->options(Periode::all()->pluck('name','id'))
                ->label('Periodo'),
            ])->columns(3)
        ];
    }
    public function save(){

        $students=$this->students;
        $insert=[];
        //dd($students);
        foreach($students as $row){

            array_push($insert, [
                'students_id'=>$row,
                'classrooms_id'=>$this->classroom,
                'periodes_id' => $this->periodes,
                'is_open'=> 1
            ]);
        }
        //dd($insert);
            StudentHasClasses::insert($insert);
            return redirect('admin/student-has-classes');
}
}
