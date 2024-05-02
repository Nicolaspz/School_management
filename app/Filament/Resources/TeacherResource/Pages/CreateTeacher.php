<?php

namespace App\Filament\Resources\TeacherResource\Pages;

use App\Filament\Resources\TeacherResource;
use App\Models\Teacher;
use App\Models\User;
use Filament\Actions;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
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
        //$get['name'];
        //dd($userData);
        return redirect()->to('admin/teachers');
}
}
