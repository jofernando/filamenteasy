<?php

namespace App\Filament\Resources\EventoResource\RelationManagers;

use App\Models\Modalidade;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ModalidadeRelationManager extends RelationManager
{
    protected static string $relationship = 'modalidades';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nome')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                Forms\Components\DatePicker::make('submissao_inicio')
                    ->required(),
                Forms\Components\DatePicker::make('submissao_fim')
                    ->required()
                    ->after('submissao_inicio'),
                Forms\Components\DatePicker::make('avaliacao_inicio')
                    ->required(),
                Forms\Components\DatePicker::make('avaliacao_fim')
                    ->required()
                    ->after('avaliacao_inicio'),
                Forms\Components\DatePicker::make('resultado')
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nome')
            ->columns([
                Tables\Columns\TextColumn::make('nome'),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make()
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                ]),
            ])
            ->modifyQueryUsing(fn (Builder $query) => $query->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]));
    }
}
