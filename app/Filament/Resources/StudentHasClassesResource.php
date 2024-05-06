<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudentHasClassesResource\Pages;
use App\Filament\Resources\StudentHasClassesResource\RelationManagers;
use App\Models\Classroom;
use App\Models\homerooms;
use App\Models\Periode;
use App\Models\Student;
use App\Models\StudentHasClasses;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StudentHasClassesResource extends Resource
{
    protected static ?string $model = StudentHasClasses::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube-transparent';
    protected static ?string $navigationLabel = 'Turmas-Estudantes';
    protected static ?string $navigationGroup = 'Académico';

    /*public static function shouldRegisterNavigation(): bool
    {
        if(auth()->user()->can('role-permission'))
        return true;
        else
        return false;
    }*/

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make([
                    Select::make('students_id')
                    ->searchable()
                    ->multiple()
                    ->options(Student::all()->pluck('name','id'))
                    ->label('Estudante'),
                    Select::make('classrooms_id')
                    ->searchable()
                    ->options(Classroom::all()->pluck('name','id'))
                    ->label('Sala'),
                    Select::make('periodes_id')
                    ->searchable()
                    ->options(Periode::all()->pluck('name','id'))
                    ->label('Periodo'),
                ])->columns(3)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('students.name')
                ->label('nome do estudante'),
                TextColumn::make('classrooms.name')
                ->label('Turma'),
                TextColumn::make('periodes.name')
                ->label('Periodo')

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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStudentHasClasses::route('/'),
            'create' => Pages\FormStudentClass::route('/create'),
            'edit' => Pages\EditStudentHasClasses::route('/{record}/edit'),
        ];
    }

}
