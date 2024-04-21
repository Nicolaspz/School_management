<?php

namespace App\Filament\Student\Resources;

use App\Filament\Student\Resources\NotaResource\Pages;
use App\Filament\Student\Resources\NotaResource\RelationManagers;
use App\Models\Classroom;
use App\Models\Nota;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class NotaResource extends Resource
{
    protected static ?string $model = Nota::class;

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
                SelectFilter::make('class_id')
                ->options(
                    Classroom::whereHas('students', function($query){
                        $query->where('user_id', Auth::user()->id);
                    })->groupBy('name', 'id')->pluck('name','id')
                )
            ])
            ->actions([
                //Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //Tables\Actions\DeleteBulkAction::make(),
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
    public static function getEloquentQuery(): Builder
    {
     return parent::getEloquentQuery()->whereHas('student.user',function($query){
        $query->where('id', Auth::user()->id);
     });
    }
}
