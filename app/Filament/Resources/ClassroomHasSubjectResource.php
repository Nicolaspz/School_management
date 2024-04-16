<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClassroomHasSubjectResource\Pages;
use App\Filament\Resources\ClassroomHasSubjectResource\RelationManagers;
use App\Models\Classroom;
use App\Models\ClassroomHasSubject;
use App\Models\Subject;
use Filament\Forms;
use Filament\Forms\Components\Select;
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

    public static function form(Form $form): Form
    {
        return $form
    ->schema([
        Select::make('classroom_id') // Alterado de 'class' para 'classroom_id'
            ->options(Classroom::all()->pluck('name', 'id'))
            ->label('Class'),
        Select::make('subject_id')
            ->options(Subject::all()->pluck('name', 'id'))
            ->multiple()
            ->label('Disciplina')
    ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('class.name')
                ->label('Turma'),
                TextColumn::make('subjects.name')
                ->label('Disciplina')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageClassroomHasSubjects::route('/'),
        ];
    }
}
