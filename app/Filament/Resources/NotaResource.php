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
use App\Models\Term;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class NotaResource extends Resource
{
    protected static ?string $model = Nota::class;

    protected static ?string $navigationIcon = 'heroicon-o-light-bulb';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                ->schema([
                    Select::make('class_id')
                ->options(Classroom::all()->pluck('name','id'))
                ->label('Turma'),
                Select::make('periode_id')
                ->searchable()
                ->options(Periode::all()->pluck('name','id'))
                ->label('Periodo'),
                Select::make('subject_id')
                ->options(Subject::all()->pluck('name','id'))
                ->label('Discipplina'),
                Select::make('term')
                ->options(Term::all()->pluck('name','id'))
                ->label('Trimestre')
                ->required(),
                Select::make('student_id')
                ->required()
                ->options(Student::all()->pluck('name','id'))
                ->label('Estudante'),
                TextInput::make('p1')
                ->label('P1')
                ->rules([
                    fn (Get $get): Closure => function (string $attribute, $value, Closure $fail) use ($get) {
                        if ($get('p1') < 6 || $get('p1') > 20) {
                            $fail("A nota deve ser de 6 a 20");
                        }
                    },
                ]),
                TextInput::make('p2')
                ->rules([
                    fn (Get $get): Closure => function (string $attribute, $value, Closure $fail) use ($get) {
                        if ($get('p1') < 6 || $get('p1') > 20) {
                            $fail("A nota deve ser de 6 a 20");
                        }
                    },
                ])
                ->label('P2')
                ->rules([
                    fn (Get $get): Closure => function (string $attribute, $value, Closure $fail) use ($get) {
                        if ($get('p1') < 6 || $get('p1') > 20) {
                            $fail("A nota deve ser de 6 a 20");
                        }
                    },
                ]),
                TextInput::make('mac')
                ->rules([
                    fn (Get $get): Closure => function (string $attribute, $value, Closure $fail) use ($get) {
                        if ($get('p1') < 6 || $get('p1') > 20) {
                            $fail("A nota deve ser de 6 a 20");
                        }
                    },
                ])
                ->label('Mac')

                ])->columns(3)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('student.name')
                ->label('Estudante')->searchable(),
                TextColumn::make('subject.name')
                ->searchable()
                ->label('Disciplina'),
                TextColumn::make('p1')
                ->label('P1'),
                TextColumn::make('p2')
                ->label('P2'),
                TextColumn::make('mac')
                ->label('MAC'),
                TextColumn::make('mt')
                ->label('MT'),
                /*TextColumn::make('periode.name')
                ->label('Ano Lectivo')*/
            ])
            ->filters([
               /* SelectFilter::make('class_id')
                ->options(
                    Classroom::whereHas('students', function($query){
                        $query->where('user_id', Auth::user()->id);
                    })->groupBy('name', 'id')->pluck('name','id')
                )*/
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

    public static function getEloquentQuery(): Builder
{
    $query = parent::getEloquentQuery();

    // Verifica o role do usuário
    $user = Auth::user();
    if ($user->hasRole('estudante')) {
        // Se o usuário é um estudante, filtra para mostrar apenas as notas dele
        $query->whereHas('student.user', function ($q) use ($user) {
            $q->where('id', $user->id);
        });
    } elseif ($user->hasRole('professor')) {
        $teacherId = $user->teacher->user_id;
        //git statusdd($teacherId);
        $query->where('teacher_id', $teacherId);
    }
    elseif ($user->hasRole('super_admin')){

    }


    return $query;
}
}
