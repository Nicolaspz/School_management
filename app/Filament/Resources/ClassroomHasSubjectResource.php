<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClassroomHasSubjectResource\Pages;
use App\Filament\Resources\ClassroomHasSubjectResource\RelationManagers;
use App\Models\ClassroomHasSubject;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ClassroomHasSubjectResource extends Resource
{
    protected static ?string $model = ClassroomHasSubject::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Disciplina Turma';
    protected static ?string $navigationGroup = 'Académico';



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
               //TextInput::make('');
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('subject.name'),
                TextColumn::make('teachers.name')->searchable(),
                TextColumn::make('class.name'),
                TextColumn::make('class.curso.name')->searchable()
                ])
            ->filters([

            ])
            ->actions([
                Tables\Actions\DeleteAction::make(),
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
    public static function getTitle(): string
    {
        return 'Título Personalizado';
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClassroomHasSubjects::route('/'),
            'create' => Pages\CreateClassroomHasSubject::route('/create'),
            'edit' => Pages\EditClassroomHasSubject::route('/{record}/edit'),
        ];
    }
}
