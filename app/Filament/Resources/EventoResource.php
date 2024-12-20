<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventoResource\Pages;
use App\Filament\Resources\EventoResource\RelationManagers;
use App\Filament\Resources\EventoResource\RelationManagers\AreasRelationManager;
use App\Filament\Resources\EventoResource\RelationManagers\ArquivosRelationManager;
use App\Filament\Resources\EventoResource\RelationManagers\CategoriasRelationManager;
use App\Filament\Resources\EventoResource\RelationManagers\QuestionariosRelationManager;
use App\Filament\Resources\EventoResource\RelationManagers\ModalidadesRelationManager;
use App\Filament\Resources\EventoResource\RelationManagers\RevisoresRelationManager;
use App\Filament\Resources\EventoResource\RelationManagers\TrabalhosRelationManager;
use App\Models\Evento;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EventoResource extends Resource
{
    protected static ?string $model = Evento::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nome')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\MarkdownEditor::make('descricao')
                    ->required()
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\Select::make('tipo')
                    ->required()
                    ->options(Evento::TIPOS),
                Forms\Components\Select::make('recolhimento')
                    ->required()
                    ->options(Evento::RECOLHIMENTOS),
                Forms\Components\DatePicker::make('inicio')
                    ->required(),
                Forms\Components\DatePicker::make('fim')
                    ->required()
                    ->after('inicio'),
                Forms\Components\FileUpload::make('banner')
                    ->disk('public')
                    ->directory('banners')
                    ->image()
                    ->maxSize(2048),
                Forms\Components\FileUpload::make('logo')
                    ->disk('public')
                    ->directory('logos')
                    ->image()
                    ->maxSize(2048),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nome')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tipo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('recolhimento')
                    ->searchable(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Configurar'),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            ArquivosRelationManager::class,
            AreasRelationManager::class,
            ModalidadesRelationManager::class,
            TrabalhosRelationManager::class,
            RevisoresRelationManager::class,
            CategoriasRelationManager::class,
            QuestionariosRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEventos::route('/'),
            'create' => Pages\CreateEvento::route('/create'),
            'view' => Pages\ViewEvento::route('/{record}'),
            'edit' => Pages\EditEvento::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereBelongsTo(auth()->user())
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
