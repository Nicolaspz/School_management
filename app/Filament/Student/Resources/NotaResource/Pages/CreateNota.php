<?php

namespace App\Filament\Student\Resources\NotaResource\Pages;


use Filament\Actions;
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
use App\Models\User;
use Closure;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;

use Illuminate\Support\Facades\Auth;
use Filament\Resources\Pages\CreateRecord;

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
                        //dd($teacherId);
                        $gradeIds = classroomHasSubject::where('teachers_id', $teacherId)
                        ->pluck('classroom_id');
                        $grade=$gradeIds[0];
                        //dd($grade);
                    $curso= Classroom::whereIn('id',$gradeIds)
                        ->pluck('cursos_id');
                        //dd($curso);
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
                ->live()
                ->afterStateUpdated(function (Set $set){
                   $set('subject_id', null);
                 }),

            Select::make('terms_id')
            ->options(Term::all()->pluck('name','id'))
            ->required()
            ->label('Trimestre')
            ->required(),
            Select::make('periode')
            ->searchable()
            ->required()
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
                            ->from('classroom_subject')
                            ->where('teachers_id', Auth::user()->teacher->id)
                            ->where('classroom_id', $get('classrooms'))
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
                ->rules([
                    'numeric', // Garante que o input é numérico
                    fn (Get $get): Closure => function (string $attribute, $value, Closure $fail) use ($get) {
                        $numericValue = floatval($value); // Converte o valor para float para garantir a comparação numérica
                        if ($numericValue < 0 || $numericValue > 20) {
                            $fail("A nota deve ser de 0 a 20.");
                        }
                    },
                ])
                ->label('P1')
                ->rules([
                    'numeric', // Garante que o input é numérico
                    fn (Get $get): Closure => function (string $attribute, $value, Closure $fail) use ($get) {
                        $numericValue = floatval($value); // Converte o valor para float para garantir a comparação numérica
                        if ($numericValue < 0 || $numericValue > 20) {
                            $fail("A nota deve ser de 0 a 20.");
                        }
                    },
                ]),
                TextInput::make('p2')
                ->rules([
                    'numeric', // Garante que o input é numérico
                    fn (Get $get): Closure => function (string $attribute, $value, Closure $fail) use ($get) {
                        $numericValue = floatval($value); // Converte o valor para float para garantir a comparação numérica
                        if ($numericValue < 0 || $numericValue > 20) {
                            $fail("A nota deve ser de 0 a 20.");
                        }
                    },
                ])
                ->label('P2')
                ->rules([
                    'numeric', // Garante que o input é numérico
                    fn (Get $get): Closure => function (string $attribute, $value, Closure $fail) use ($get) {
                        $numericValue = floatval($value); // Converte o valor para float para garantir a comparação numérica
                        if ($numericValue < 0 || $numericValue > 20) {
                            $fail("A nota deve ser de 0 a 20.");
                        }
                    },
                ]),
                TextInput::make('mac')
                ->rules([
                    'numeric', // Garante que o input é numérico
                    fn (Get $get): Closure => function (string $attribute, $value, Closure $fail) use ($get) {
                        $numericValue = floatval($value); // Converte o valor para float para garantir a comparação numérica
                        if ($numericValue < 0 || $numericValue > 20) {
                            $fail("A nota deve ser de 0 a 20.");
                        }
                    },
                ])
                ->label('Mac')


            ])->columns(4)
           ])
            ]);
    }


    public function save(){
        $get = $this->form->getState();
        $insert = [];

        foreach($get['nilaistudents'] as $row){
            $existingRecord = Nota::where('student_id', $row['student'])
                                  ->where('terms_id', $get['terms_id'])
                                  ->where('subject_id', $get['subject_id'])
                                  ->where('periode_id', $get['periode'])
                                  ->first();

            // Calcular a média das notas
            $notas = collect([$row['p1'], $row['p2'], $row['mac']])->filter()->values();
            $media = $notas->sum() / 3; // Calcula a média apenas dos valores não nulos

            if ($existingRecord) {
                // Atualizar registro existente
                $existingRecord->update([
                    'p1' => $row['p1'] ?? $existingRecord->p1,
                    'p2' => $row['p2'] ?? $existingRecord->p2,
                    'mac' => $row['mac'] ?? $existingRecord->mac,
                    'mt' => $media  // Atualiza a média
                ]);
            } else {
                // Preparar para nova inserção
                $insert[] = [
                    'class_id' => $get['classrooms'],
                    'student_id' => $row['student'],
                    'periode_id' => $get['periode'],
                    'terms_id' => $get['terms_id'],
                    'teacher_id' => Auth::user()->id,
                    'subject_id' => $get['subject_id'],
                    'p1' => $row['p1'],
                    'p2' => $row['p2'],
                    'mac' => $row['mac'],
                    'mt' => $media  // Define a média na inserção
                ];
            }

            if ($row['p1'] < 10) {
                $studentUserId = Student::where('id', $row['student'])->value('user_id');
                $studentUser = User::find($studentUserId);

                if ($studentUser) {
                    Notification::make()
                        ->title('Nota Baixa')
                        ->success()
                        ->body('Sua nota de')
                        ->sendToDatabase($studentUser)
                        ->send();
                }
            }
        }

        if (!empty($insert)) {
            Nota::insert($insert);
        }

        return redirect()->to('student/notas');
    }
}
