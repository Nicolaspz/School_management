<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ConsultaResource\Pages;
use App\Filament\Resources\ConsultaResource\RelationManagers;
use App\Models\Consulta;
use App\Models\psicologo;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class ConsultaResource extends Resource
{
    protected static ?string $model = Consulta::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
              //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
               TextColumn::make('descricao'),
               TextColumn::make('status'),
               TextColumn::make('psicologo.name'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    BulkAction::make('Actualizar estatus')
                    ->icon('heroicon-m-check')
                    ->requiresConfirmation()
                    ->form([
                        Select::make('Status')
                        ->label('Status')
                        ->options(['Aceite'=>'Aceite','Pendente'=> 'Pendente','consultado'=>'consultado'])
                        ->required()
                    ])
                    ->action(function (Collection $records, array $data){
                        return $records->each(function ($records) use ($data){
                            $id=$records->id;
                            Consulta::where('id',$id)->update(['status'=>$data['Status']]);
                        });
                    }),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListConsultas::route('/'),
            'create' => Pages\CreateConsulta::route('/create'),
            'edit' => Pages\EditConsulta::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        // Verifica o role do usuário
        $user = Auth::user();
        if ($user->hasRole('estudante')) {
            // Se o usuário é um estudante, filtra para mostrar apenas as notas dele
            $query->whereHas('student.user', function ($q) use ($user) {
                $q->where('id', $user->id);
            });
        } elseif ($user->hasRole('psicologo')) {
            $teacherId = $user->psicologo->user_id;
            //git statusdd($teacherId);
            $query->where('psicologo_id', $teacherId);
        }
        elseif ($user->hasRole('super_admin')){

        }


        return $query;
    }
}
