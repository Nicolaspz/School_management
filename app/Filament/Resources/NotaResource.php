<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NotaResource\Pages;
use App\Filament\Resources\NotaResource\RelationManagers;
use App\Models\CategoryNilai;
use App\Models\Classroom;
use App\Models\Nota;
use App\Models\Periode;
use App\Models\Student;
use App\Models\Subject;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class NotaResource extends Resource
{
    protected static ?string $model = Nota::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                ->schema([
                    Select::make('class_id')
                ->options(Classroom::all()->pluck('name','id'))
                ->label('Class'),
                Select::make('periode_id')
                ->searchable()
                ->options(Periode::all()->pluck('name','id'))
                ->label('Periodo'),
                Select::make('subject_id')
                ->options(Subject::all()->pluck('name','id'))
                ->label('Discipplina'),
                Select::make('category_notas_id')
                ->options(CategoryNilai::all()->pluck('name','id'))
                ->label('categoria da nota'),
                Select::make('student_id')
                ->options(Student::all()->pluck('name','id'))
                ->label('Estudante'),
                TextInput::make('nota')
                ])->columns(3)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('student.name')
                ->label('Estudante'),
                TextColumn::make('subject.name')
                ->label('Disciplina'),
                TextColumn::make('category_nilai.name')
                ->label('Categoria'),
                TextColumn::make('nota')
                ->label('Nota'),
                TextColumn::make('periode.name')
                ->label('Ano Lectivo')
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
            'index' => Pages\ListNotas::route('/'),
            'create' => Pages\CreateNota::route('/create'),
            'edit' => Pages\EditNota::route('/{record}/edit'),
        ];
    }
}
