<?php

namespace App\Filament\Resources\ConsultaResource\Pages;

use App\Filament\Resources\ConsultaResource;
use App\Models\consulta;
use App\Models\psicologo;
use Filament\Actions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateConsulta extends CreateRecord
{
    protected static string $resource = ConsultaResource::class;
    protected static string $view ='filament.resources.consulta.pages.form-consulta';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
               Select::make('psicologo_id')
               ->options(psicologo::all()->pluck('name','id'))
               ->label('psicólogo')
               ->required(),
               Textarea::make('descricao')
                ->rows(10)
                ->cols(20)
                ->required()

            ]);
    }

    public function save(){

         $estudante_id=Auth::user()->student->id;
         $estudante_name=Auth::user()->student->name;
        $get=$this->form->getState();

          $consulta=consulta::create([
                'psicologo_id' =>$get['psicologo_id'],
                'students_id' => $estudante_id,
                'descricao' => $get['descricao'],
                //'status'=>$get['departments_id'],

                // Outros campos de dados pessoais do estudante
            ]);

            if ($consulta) {
                Notification::make()
                    ->title('Pedido de Consulta')
                    ->success()
                    ->body('O Estudante ' . $estudante_name . ' Enviou pedido de Consulta')
                    ->sendToDatabase($get['psicologo_id'])
                    ->send();
            }



        return redirect()->to('admin/consultas');
    }
}
