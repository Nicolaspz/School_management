<?php

namespace App\Filament\Resources\PsicologoResource\Pages;

use App\Filament\Resources\PsicologoResource;
use App\Models\Department;
use App\Models\psicologo;
use App\Models\Student;
use App\Models\StudentHasClasses;
use App\Models\User;
use Filament\Actions;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Hash;

class CreatePsicologo extends CreateRecord
{
    protected static string $resource = PsicologoResource::class;
    protected static string $view = 'filament.resources.psicologo.pages.form-psicologo';

    public function form(Form $form): Form
    {
        return $form
            ->schema([

                Card::make("Dados de Acesso")
                ->schema([
                    TextInput::make('email')
                    ->email()->required(),
                    /*TextInput::make('password')
                    ->password()
                    ->dehydrateStateUsing(fn (string $state): string=> Hash::make($state))
                    ->dehydrated(fn (?string $state): bool => filled($state))
                    ->required(fn (Page $livewire): bool=> $livewire instanceof CreateRecord),
                    /*Select::make('roles_id')->multiple()
                    ->relationship('roles', 'name')*/
                ]),

                Card::make("Dados Gerais")
                ->schema([
                TextInput::make('name')
                ->label('Nome do psicologo')
                ->required(),
                Select::make('departments_id')
                ->options(Department::all()->pluck('name_department', 'id'))
                ->required()
                ->label('Departamento'),
                TextInput::make('address')
                    ->label('Endereço'),
                FileUpload::make('profile')
                ->disk('public')
                ->directory('psicologo')
                ->required()
                ])->columns(2),

            ]);

    }

    public function save(){
        $get=$this->form->getState();


        $latestStudent = Student::latest()->first();
        //dd($latestStudent);

        if ($latestStudent) {
            $latestStudentId = $latestStudent->id;
            //dd($latestStudentId);
        } else {
            $latestStudentId=0;
        }
        $nis=$latestStudentId + 1;
            $pass="12345678";
        $user = User::create([
            'name' => $get['name'],
            'email' => $get['email'],
            'password' => Hash::make($pass),
        ]);




            psicologo::create([
                'users_id' => $user->id,
                'name' => $get['name'],
                'profile' => $get['profile'],
                'departments_id'=>$get['departments_id'],
                'address'=>$get['address']
                // Outros campos de dados pessoais do estudante
            ]);
            $user->assignRole('psicologo');


        return redirect()->to('admin/psicologos');
    }

}
