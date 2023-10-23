<?php

namespace App\Filament\Resources\EventoResource\RelationManagers;

use App\Forms\Components\RepeaterWizard;
use App\Models\Inscricao\Categoria;
use App\Models\Modalidade;
use Closure;
use App\Models\Questionario\Questao;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class QuestionariosRelationManager extends RelationManager
{
    protected static string $relationship = 'questionarios';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\MorphToSelect::make('questionavel')
                    ->types([
                        Forms\Components\MorphToSelect\Type::make(Modalidade::class)
                            ->titleAttribute('nome'),
                        Forms\Components\MorphToSelect\Type::make(Categoria::class)
                            ->titleAttribute('nome'),
                    ]),
                Forms\Components\TextInput::make('nome')
                    ->required()
                    ->maxLength(255),
                Forms\Components\RichEditor::make('descricao')
                    ->required()
                    ->maxLength(65535),
                RepeaterWizard::make('questoes')
                    ->schema([
                        Forms\Components\RichEditor::make('nome')
                            ->required()
                            ->maxLength(65535),
                        Forms\Components\Select::make('tipo')
                            ->required()
                            ->options(Questao::TIPOS)
                            ->live(),
                        Forms\Components\Toggle::make('mostrar_resposta_autor')
                            ->label('Resposta visível para o autor'),
                        Forms\Components\Repeater::make('opcoes')
                            ->schema([
                                Forms\Components\RichEditor::make('nome')
                                    ->required()
                                    ->maxLength(65535),
                            ])
                            ->label('Opções')
                            ->columns(1)
                            ->relationship()
                            ->hidden(fn (Get $get): bool => empty($get('tipo')) || $get('tipo') == 'DISCURSIVA'),
                    ])
                    ->relationship()
            ])->columns(1);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nome')
            ->columns([
                Tables\Columns\TextColumn::make('nome'),
                Tables\Columns\TextColumn::make('questionavel.nome'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            ->query($evento->questionarios())
            ->inverseRelationship(static::getInverseRelationshipName())
            ->modelLabel('questionario')
            ->pluralModelLabel('questionarios')
            ->recordTitleAttribute(static::getRecordTitleAttribute())
            ->heading($this->getTableHeading() ?? static::getTitle($this->getOwnerRecord(), $this->getPageClass()))
            ->when(
                $this->getTableRecordUrlUsing(),
                fn (Table $table, ?Closure $using) => $table->recordUrl($using),
            );
    }
}
