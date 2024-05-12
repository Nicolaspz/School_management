<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClassroomResource\Pages;
use App\Filament\Resources\ClassroomResource\RelationManagers;
use App\Filament\Resources\ClassroomResource\RelationManagers\StudentRelationManager;
use App\Filament\Resources\ClassroomResource\RelationManagers\SubjectRelationManager;
use App\Models\Classroom;
use App\Models\Curso;
use App\Models\grade;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ClassroomResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = Classroom::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-group';
    protected static ?string $navigationLabel = 'Turma';
    protected static ?string $navigationGroup = 'Académico';


    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'create',
            'update',
            'delete',

        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                ->live()
                ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', \Str::slug($state))),
                TextInput::make('slug'),
                Select::make('cursos_id')
                    ->searchable()
                   ->options(fn () =>Curso::all()->pluck('name','id'))
                    ->label('Curso')
                    ->live()
                    ->required(),
                    Select::make('grade_id')
                    ->searchable()
                   ->options(fn (Get $get) =>grade::where('cursos_id',$get('cursos_id'))->pluck('name','id'))
                    ->label('Class')
                    ->live()
                    ->required(),
                    ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                ->searchable(),
                TextColumn::make('slug'),
                TextColumn::make('grade.name')
                ->label('class')
                ->searchable(),
                TextColumn::make('curso.name')->searchable(),
            ])
            ->filters([

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
            StudentRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClassrooms::route('/'),
            'create' => Pages\CreateClassroom::route('/create'),
            'edit' => Pages\EditClassroom::route('/{record}/edit'),
        ];
    }
}
