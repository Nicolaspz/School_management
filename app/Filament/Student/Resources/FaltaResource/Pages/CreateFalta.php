<?php

namespace App\Filament\Student\Resources\FaltaResource\Pages;
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
                ->options(function () use ($get) {
                    $user = Auth::user();
                    if ($user->hasRole('professor')) {
                        $data = Classroom::whereIn('id', function ($query) use ($get){
                            $query->select('classroom_id')
                            ->from('classroom_subject')
                            ->where('teachers_id', Auth::user()->teacher->id)
                            ->pluck('classroom_id');
                        })
                        ->pluck('name', 'id');
                     }
                    else{
                        $data = Classroom::all()->pluck('subject_id');

                    }

                    return $data;

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
                ->default(true)

            ])->columns(2)
           ])
            ]);
    }

    public function save(){
                $get=$this->form->getState();
                $get['aulas'];
                $user = Auth::user();
                $insert = [];
                //dd($get['aulas']);
        // Itera sobre cada estudante listado no repeater 'nilaistudents'
                foreach ($get['nilaistudents'] as $row) {
            // Adiciona ao array de inserção cada registro de falta para os estudantes

            array_push($insert, [
                'aulas_id' => $get['aulas'],  // ID da aula selecionada
                'students_id' => $row['student'],  // ID do estudante
                'falta' => $row['falta'],  // Estado da falta (presença/ausência)
                'teacher_id' => $user->teacher->id,
            ]);
        }

        // Insere todos os dados coletados na tabela 'Falta'
        Falta::insert($insert);

        // Redireciona para a página de visualização de faltas após a inserção
        return redirect()->to('student/faltas');
    }
}
