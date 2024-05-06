<?php

namespace App\Filament\Resources\StudentsResource\Pages;

use App\Filament\Resources\StudentsResource;
use App\Models\Student;
use App\Models\User;
use Filament\Actions;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CreateStudents extends CreateRecord
{
    protected static string $resource = StudentsResource::class;
    protected static string $view = 'filament.resources.student-resource.pages.create_student';

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

                Card::make("Dados Pessoais")
                ->schema([
                TextInput::make('name')
                ->label('Nome do Estudante')
                ->required(),
                Select::make('gender')
                ->options([
                    "Male",
                     "Female"])->required()
                ->hidden(),
                DatePicker::make('birthday')
                ->label('Aniversario'),

                Select::make('religion')
                ->options([
                    "Islam",
                     "Katolik",
                     "Protestan",
                      "Hindu",
                      "Buddha",
                      "Khonghucu"
                ])
              ->required()
              ->hidden()
              ,
                TextInput::make('contact')
                    ->label('Contacto')
                    ->hidden(),
                FileUpload::make('profile')

                ->disk('public')
                ->directory('studants')
                ])->columns(2)
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



        try {
            $student = Student::create([
                'user_id' => $user->id,
                'nis' => $nis,
                'name' => $get['name'],
                //'gender' => $get['gender'],
                'birthday' => $get['birthday'],
                //'religion' => $get['religion'],
                //'contact' => $get['contact'],
                'profile' => $get['profile'],
                // Outros campos de dados pessoais do estudante
            ]);
            $user->assignRole('estudante');

            // Verifica se o aluno foi salvo corretamente
            if ($student) {
                // Commit na transação se tudo estiver OK
                DB::commit();
            } else {
                // Se o aluno não foi salvo corretamente, reverta a transação
                DB::rollback();
                // Excluir o usuário usando o id recuperado
                User::where('id', $user->id)->delete();
            }
        } catch (\Exception $e) {
            // Se ocorrer uma exceção, reverta a transação e lidere com o erro
            DB::rollback();
            // Lidar com a exceção, por exemplo, registrar ou lançar novamente
            throw $e;
        }
        return redirect()->to('admin/students');
}

}
