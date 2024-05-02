<?php

namespace App\Filament\Resources\GradeResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
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
            //->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                ->label('Disciplina'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                 Tables\Actions\CreateAction::make(),
               /*Tables\Actions\AttachAction::make()
                ->recordSelect(
                    fn (Select $select)=> $select->placeholder('Selecione a Disciplina')
                ),
                /* ->form(fn (AttachAction $action): array =>[
                     $action->getRecordSelect(),
                     TextInput::make('description')->required(),
                 ]),*/
            ])
            ->actions([
                //Tables\Actions\EditAction::make(),
                //Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
