<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AulaResource\Pages;
use App\Filament\Resources\AulaResource\RelationManagers;
use App\Filament\Resources\FaltaResource\RelationManagers\FaltaResourceRelationManager;
use App\Models\Aula;
use App\Models\Classroom;
use App\Models\classroomHasSubject;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class AulaResource extends Resource
{
    protected static ?string $model = Aula::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('sumario')
                ->label('Sumário'),
                Textarea::make('title')
                ->label('Assunto'),
                DatePicker::make('data')
                ->label('data'),
                Select::make('classrooms_id')
                ->options(function () {
                    $user = Auth::user();  // Pega o usuário logado
                    if ($user->hasRole('professor')) {  // Verifica se o usuário tem o papel de professor
                        $teacherId = $user->teacher->id;  // Pega o ID do professor logado
                        $classroomIds = classroomHasSubject::where('teachers_id', $teacherId)  // Filtra classroomHasSubject pelo ID do professor
                            ->pluck('classroom_id');  // Obtém os IDs das salas de aula

                        return Classroom::whereIn('id', $classroomIds)  // Obtém as salas de aula que correspondem aos IDs filtrados
                            ->pluck('name', 'id');  // Prepara a lista de opções para o Select
                    } else {
                        // Se não é professor, pode retornar todas as salas de aula ou uma lista vazia
                        return Classroom::pluck('name', 'id');  // Retorna todas as salas de aula
                        // ou
                        // return [];  // Retorna uma lista vazia
                    }
                })
                ->label('Turma')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sumario')
                ->label('Sumário'),
                TextColumn::make('title')
                ->label('Assunto'),
                TextColumn::make('data')
                ->label('data'),
                TextColumn::make('classroom.name')
                   ->label('Turma'),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            FaltaResourceRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAulas::route('/'),
            'create' => Pages\CreateAula::route('/create'),
            'edit' => Pages\EditAula::route('/{record}/edit'),
        ];
    }
}
