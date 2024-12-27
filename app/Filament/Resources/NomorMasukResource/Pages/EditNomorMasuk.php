<?php

namespace App\Filament\Resources\NomorMasukResource\Pages;

use App\Filament\Resources\NomorMasukResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditNomorMasuk extends EditRecord
{
    protected static string $resource = NomorMasukResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
