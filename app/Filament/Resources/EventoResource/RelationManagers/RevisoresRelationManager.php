<?php

namespace App\Filament\Resources\EventoResource\RelationManagers;

use App\Models\User;
use Closure;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RevisoresRelationManager extends RelationManager
{
    protected static string $relationship = 'revisores';

    public function form(Form $form): Form
    {
        $evento = $this->ownerRecord;
        $areas = $evento->areas()->pluck('nome', 'id')->all();
        $modalidades = $evento->modalidades()->pluck('nome', 'id')->all();
        return $form
            ->schema([
                Forms\Components\TextInput::make('email')
                    ->required()
                    ->email()
                    ->maxLength(255)
                    ->disabled(function (string $operation) {
                        return $operation != 'create';
                    }),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->disabled(function (string $operation) {
                        return $operation != 'create';
                    }),
                Forms\Components\CheckboxList::make('areas')
                    ->label('Áreas')
                    ->required()
                    ->options($areas),
                Forms\Components\CheckboxList::make('modalidades')
                    ->required()
                    ->options($modalidades),
            ]);
    }

    public function table(Table $table): Table
    {

        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Nome'),
                Tables\Columns\TextColumn::make('email'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                ->using(function (array $data, string $model): Model {
                    $qry = User::where('email', $data['email']);
                    if ($qry->exists()) {
                        $user = $qry->first();
                    } else {
                        $password = Str::random(12);
                        $user = User::create([
                            'email' => $data['email'],
                            'password' => Hash::make($password),
                            'name' => $data['name'],
                        ]);
                    }
                    $user->modalidades_revisaveis()->attach($data['modalidades']);
                    $user->areas_revisaveis()->attach($data['areas']);
                    return $user;
                }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                ->mutateRecordDataUsing(function (array $data): array {
                    $user = User::find($data['id']);
                    $data['areas'] = $user->areas_revisaveis()->pluck('revisores.revisores_id')->all();
                    $data['modalidades'] = $user->modalidades_revisaveis()->pluck('revisores.revisores_id')->all();

                    return $data;
                })
                ->using(function (Model $record, array $data): Model {
                    $record->modalidades_revisaveis()->sync($data['modalidades']);
                    $record->areas_revisaveis()->sync($data['areas']);

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

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return true;
    }

    protected function makeTable(): Table
    {
        $evento = $this->ownerRecord;
        return $this->makeBaseRelationshipTable()
            ->query($evento->revisores())
            ->inverseRelationship(static::getInverseRelationshipName())
            ->modelLabel('revisor')
            ->pluralModelLabel('revisores')
            ->recordTitleAttribute(static::getRecordTitleAttribute())
            ->heading($this->getTableHeading() ?? static::getTitle($this->getOwnerRecord(), $this->getPageClass()))
            ->when(
                $this->getTableRecordUrlUsing(),
                fn (Table $table, ?Closure $using) => $table->recordUrl($using),
            );
    }
}
