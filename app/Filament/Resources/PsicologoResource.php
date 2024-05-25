<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PsicologoResource\Pages;
use App\Filament\Resources\PsicologoResource\RelationManagers;
use App\Models\Psicologo;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PsicologoResource extends Resource
{
    protected static ?string $model = Psicologo::class;

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
               TextColumn::make('name'),
               TextColumn::make('department.name_department')
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
            'index' => Pages\ListPsicologos::route('/'),
            'create' => Pages\CreatePsicologo::route('/create'),
            'edit' => Pages\EditPsicologo::route('/{record}/edit'),
        ];
    }
}
