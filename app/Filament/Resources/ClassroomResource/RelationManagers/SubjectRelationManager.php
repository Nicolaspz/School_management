<?php

namespace App\Filament\Resources\ClassroomResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DetachAction;
use Filament\Tables\Actions\DetachBulkAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SubjectRelationManager extends RelationManager
{
    protected static string $relationship = 'Subject';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //Tables\Actions\CreateAction::make(),
               /* Tables\Actions\AttachAction::make()
                ->recordSelect(
                    fn (Select $select)=> $select->placeholder('Selecione a Disciplina')
                 )
                 ->form(fn (AttachAction $action): array =>[
                     $action->getRecordSelect(),
                     TextInput::make('description')->required(),
                 ]),*/
            ])
            ->bulkActions([
                BulkActionGroup::make([
                   // DetachBulkAction::make(),
                ])
            ])
            ->actions([
                //Tables\Actions\EditAction::make(),
                //Tables\Actions\DetachAction::make(),
                //Tables\Actions\DeleteAction::make(),
            ]);

    }
}
