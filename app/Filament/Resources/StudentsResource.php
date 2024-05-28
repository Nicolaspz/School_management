<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudentsResource\Pages;
use App\Filament\Resources\StudentsResource\RelationManagers;
use App\Models\Student;
use App\Models\Students;
use Doctrine\DBAL\Driver\Mysqli\Initializer\Options;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Infolists\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Infolists\Components\Section;

use Filament\Forms\Components\Select;
use Filament\Infolists\Components\Split;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

use function Laravel\Prompts\select;

class StudentsResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationLabel = 'Estudante';
    protected static ?string $navigationGroup = 'Académico';

   /* public static function shouldRegisterNavigation(): bool
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
                Card::make()
                ->schema([
                    TextInput::make('nis')
                    ->label('NIS'),
                TextInput::make('name')
                ->label('Nome do Estudante')
                ->required(),
                Select::make('gender')
                ->options([
                    "Masculino",
                     "Femenino"
                    ])->label('género'),
                DatePicker::make('birthday')
                ->label('Aniversario'),

                TextInput::make('contact')
                    ->label('Contacto'),
                FileUpload::make('profile')
                ->required()
                ->disk('public')
                ->directory('studants')
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nis')
                    ->label('NIS')
                    ->searchable(),
                TextColumn::make('name')
                ->searchable(),
                //TextColumn::make('gender'),
                TextColumn::make('birthday')
                ->label('Aniversario'),
                TextColumn::make('gender')
                ->label('género'),
                TextColumn::make('contact')->label('Contacto'),
                ImageColumn::make('profile'),
                TextColumn::make('status')
                ->formatStateUsing(fn (string $state): string => ucwords("$state"))
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),


            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    BulkAction::make('Actualizar estatus')
                    ->icon('heroicon-m-check')
                    ->requiresConfirmation()
                    ->form([
                        Select::make('Status')
                        ->label('Status')
                        ->options(['accept'=>'Accept','off'=> 'Off','move'=>'Move','grade'=>'Grade'])
                        ->required()

                    ])
                    ->action(function (Collection $records, array $data){
                        return $records->each(function ($records) use ($data){
                            $id=$records->id;
                            Student::where('id',$id)->update(['status'=>$data['Status']]);
                        });
                    }),

                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudents::route('/create'),
            'edit' => Pages\EditStudents::route('/{record}/edit'),
            'view'=> Pages\ViewStudent::route('/{record}'),
        ];
    }
    public static function getLabel(): ?string
    {
        $locale =app()->getLocale();

        if($locale == 'id'){

            return "Murid";
        }
        else
            return "students";

    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
        ->schema([
            Section::make()
            ->schema([
                Fieldset::make('Dados')
                    ->schema([
                       Split::make([
                        ImageEntry::make('profile')
                        ->hiddenLabel()
                        ->grow(false),
                        Grid::make(2)
                        ->schema([
                            Group::make([
                                TextEntry::make('name')
                                ->label('Nome:'),
                                TextEntry::make('nis')
                                ->label('Nº de Processo:'),
                                TextEntry::make('bi')
                                ->label('BI:'),
                                TextEntry::make('birthday')
                                ->label('Data de Nascimento:')
                            ])
                            ->inlineLabel()
                            ->columns(2),
                            Group::make([

                                TextEntry::make('contact')
                                ->label('Contacto'),
                                TextEntry::make('status')
                                ->badge()
                                ->color(fn (string $state): string => match ($state){
                                    'accept'=>'sucess',
                                    'off'=>'danger',
                                    'grade'=>'sucess',
                                    'move'=>'warning',
                                    'wait'=>'gray'
                                })
                            ])
                            ->inlineLabel()
                            ->columns(1),
                        ])
                       ])->from('lg')

                    ])->columns(1)
                ])->columns(2)

        ]);
    }
}
