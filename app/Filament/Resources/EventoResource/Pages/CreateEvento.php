<?php

namespace App\Filament\Resources\EventoResource\Pages;

use App\Filament\Resources\EventoResource;
use App\Models\Evento;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateEvento extends CreateRecord
{
    protected static string $resource = EventoResource::class;

    protected function handleRecordCreation(array $data): Evento
    {
        return auth()->user()->eventos()->create($data);
    }
}
