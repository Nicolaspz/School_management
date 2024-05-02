<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FaltaResource\Pages;
use App\Filament\Resources\FaltaResource\RelationManagers;
use App\Models\Aula;
use App\Models\Falta;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class FaltaResource extends Resource
{
    protected static ?string $model = Falta::class;

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
               TextColumn::make('students.name'),
               IconColumn::make('falta')
               ->boolean(),


            ])
            ->filters([
               /*SelectFilter::make('aulas_id')
                    ->label('Aula')
                    ->options(
                        Aula::whereHas('teachers', function ($query) {
                            $teacherId = Auth::user()->teacher->id;  // Pega o ID do professor logado
                            $query->where('teachers_id', $teacherId);  // Filtra as aulas pelo ID do professor
                        })->pluck('sumario', 'id')  // Pluck aula summaries for ID
                    ),
                    SelectFilter::make('aulas_id')
                    ->label('Aula')
                    ->options(
                        Aula::whereHas('teachers', function ($query) {
                            $teacherId = Auth::user()->teacher->id;  // Pega o ID do professor logado
                            $query->where('teachers_id', $teacherId);  // Filtra as aulas pelo ID do professor
                        })->pluck('sumario', 'id')  // Pluck aula summaries for ID
                    )*/
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFaltas::route('/'),
            'create' => Pages\CreateFalta::route('/create'),
            'edit' => Pages\EditFalta::route('/{record}/edit'),
        ];
    }
}
