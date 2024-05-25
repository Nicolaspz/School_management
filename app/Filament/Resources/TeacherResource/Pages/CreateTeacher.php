<?php

namespace App\Filament\Resources\TeacherResource\Pages;

use App\Filament\Resources\TeacherResource;
use App\Models\Classroom;
use App\Models\classroomHasSubject;
use App\Models\Curso;
use App\Models\grade;
use App\Models\Periode;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use Filament\Actions;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Pages\Page;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Hash;

class CreateTeacher extends CreateRecord
{
    protected static string $resource = TeacherResource::class;
    protected static string $view = 'filament.resources.teacher-resource.pages.create_teacher';

    public function form(Form $form): Form
    {
        return $form
            ->schema([

                Card::make("Dados de Acesso")
                ->schema([
                    TextInput::make('name')
                    ->required()
                    ->required(),
                    TextInput::make('email')
                    ->email()->required(),
                    TextInput::make('password')
                    ->password()
                    ->dehydrateStateUsing(fn (string $state): string=> Hash::make($state))
                    ->dehydrated(fn (?string $state): bool => filled($state))
                    ->required(fn (Page $livewire): bool=> $livewire instanceof CreateRecord),
                    /*Select::make('roles_id')->multiple()
                    ->relationship('roles', 'name')*/
                ]),

                Card::make("Dados Pessoais")
                ->schema([
                TextInput::make('nip'),
                TextInput::make('name'),
                Textarea::make('address'),
                FileUpload::make('profile')
                ->disk('public')
                ->directory('teachers')
                ])->columns(2),


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

        $user = User::create([
            'name' => $get['name'],
            'email' => $get['email'],
            'password' => Hash::make($get['password']),
        ]);

        $teacher = Teacher::create([
            'user_id' => $user->id,
            'nip' => $get['nip'],
            'name' => $get['name'],
            'address' => $get['address'],
            'profile'=> $get['profile'],
            //'contact' => $get['contact'],
            // Outros campos de dados pessoais do estudante
        ]);
        $user->assignRole('professor');
        classroomHasSubject::create([
            'subject_id'=> $get['subject_id'],
            'teachers_id'=>$teacher->id,
            'classroom_id'=> $get['classrooms'],
        ]);
        return redirect()->to('admin/teachers');
        }
}
