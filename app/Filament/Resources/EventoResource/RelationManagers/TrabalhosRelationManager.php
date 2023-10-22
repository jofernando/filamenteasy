<?php

namespace App\Filament\Resources\EventoResource\RelationManagers;

use App\Models\Trabalho;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TrabalhosRelationManager extends RelationManager
{
    protected static string $relationship = 'trabalhos';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nome')
                    ->label('Título')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('modalidade_id')
                    ->relationship('modalidade', 'nome')
                    ->required(),
                Forms\Components\Select::make('area_id')
                    ->relationship('area', 'nome')
                    ->required(),
                Forms\Components\Fieldset::make('Autor')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nome')
                            ->required(),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required(),
                    ]),
                Forms\Components\Repeater::make('coautores')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nome')
                            ->required(),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required(),
                    ])
                    ->columns(2),
                Forms\Components\FileUpload::make('arquivo')
                    ->required()
                    ->disk('local')
                    ->directory('trabalhos')
                    ->maxSize(2048),
            ])
            ->columns(1);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nome')
            ->columns([
                Tables\Columns\TextColumn::make('nome')
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Autor')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->using(function (array $data, string $model): Model {
                        $trabalho = new Trabalho();
                        $trabalho->nome = $data['nome'];
                        $trabalho->arquivo = $data['arquivo'];
                        $trabalho->area_id = $data['area_id'];
                        $trabalho->modalidade_id = $data['modalidade_id'];

                        $qry = User::where('email', $data['email']);
                        if ($qry->exists()) {
                            $trabalho->user_id = $qry->first()->id;
                        } else {
                            $password = Str::random(12);
                            $user = User::create([
                                'email' => $data['email'],
                                'password' => Hash::make($password),
                                'name' => $data['name'],
                            ]);
                            $trabalho->user_id = $user->id;
                        }

                        $trabalho->save();
                        if ($data['coautores']) {
                            $coautores = collect([]);
                            foreach ($data['coautores'] as $coautor) {
                                if ($coautor['email']) {
                                    $qry = User::where('email', $coautor['email']);
                                    if ($qry->exists()) {
                                        $coautores->push($qry->first()->id);
                                    } else {
                                        $password = Str::random(12);
                                        $user = User::create([
                                            'email' => $coautor['email'],
                                            'password' => Hash::make($password),
                                            'name' => $coautor['name'],
                                        ]);
                                        $coautores->push($user->id);
                                    }
                                }
                            }
                            $trabalho->coautores()->attach($coautores);
                        }
                        return $trabalho;
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->mutateRecordDataUsing(function (array $data): array {
                        $coautores = User::whereRelation('cotrabalhos', 'trabalhos.id', $data['id'])->get();
                        $autor = User::find($data['user_id']);
                        $data['name'] = $autor->name;
                        $data['email'] = $autor->email;
                        $data['coautores'] = $coautores->map(fn ($item) => ['name' => $item->name, 'email' => $item->email])->all();
                        return $data;
                    })
                    ->using(function (Model $record, array $data): Model {
                        $record->nome = $data['nome'];
                        $record->arquivo = $data['arquivo'];
                        $record->area_id = $data['area_id'];
                        $record->modalidade_id = $data['modalidade_id'];

                        $qry = User::where('email', $data['email']);
                        if ($qry->exists()) {
                            $record->user_id = $qry->first()->id;
                        } else {
                            $password = Str::random(12);
                            $user = User::create([
                                'email' => $data['email'],
                                'password' => Hash::make($password),
                                'name' => $data['name'],
                            ]);
                            $record->user_id = $user->id;
                        }

                        $record->save();
                        if ($data['coautores']) {
                            $coautores = collect([]);
                            foreach ($data['coautores'] as $coautor) {
                                if ($coautor['email']) {
                                    $qry = User::where('email', $coautor['email']);
                                    if ($qry->exists()) {
                                        $coautores->push($qry->first()->id);
                                    } else {
                                        $password = Str::random(12);
                                        $user = User::create([
                                            'email' => $coautor['email'],
                                            'password' => Hash::make($password),
                                            'name' => $coautor['name'],
                                        ]);
                                        $coautores->push($user->id);
                                    }
                                }
                            }
                            $record->coautores()->sync($coautores);
                        }
                        return $record;
                    }),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
