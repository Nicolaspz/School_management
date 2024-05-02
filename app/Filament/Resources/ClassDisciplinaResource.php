<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClassDisciplinaResource\Pages;
use App\Filament\Resources\ClassDisciplinaResource\RelationManagers;
use App\Models\ClassDisciplina;
use App\Models\grade;
use App\Models\Subject;
use App\Models\Teacher;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ClassDisciplinaResource extends Resource
{
    protected static ?string $model = ClassDisciplina::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('grade_id') // Alterado de 'class' para 'classroom_id'
            ->options(grade::all()->pluck('name', 'id'))
            ->label('Class'),
            Select::make('teachers_id') // Alterado de 'class' para 'classroom_id'
            ->options(Teacher::all()->pluck('name', 'id'))
            ->label('Professor')
            ->required(),
            Select::make('subject_id')
            ->options(Subject::all()->pluck('name', 'id'))
           ->label('Disciplina')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('class.name')
                ->label('Classe'),
                TextColumn::make('subjects.name')
                ->label('Disciplina'),
                TextColumn::make('teachers.name')
                ->label('Professor')
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
            'index' => Pages\ListClassDisciplinas::route('/'),
            'create' => Pages\CreateClassDisciplina::route('/create'),
            'edit' => Pages\EditClassDisciplina::route('/{record}/edit'),
        ];
    }
}
