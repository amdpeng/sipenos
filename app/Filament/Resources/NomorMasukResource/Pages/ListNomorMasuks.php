<?php

namespace App\Filament\Resources\NomorMasukResource\Pages;

use App\Filament\Resources\NomorMasukResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNomorMasuks extends ListRecords
{
    protected static string $resource = NomorMasukResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Create New'),
        ];
    }
}
