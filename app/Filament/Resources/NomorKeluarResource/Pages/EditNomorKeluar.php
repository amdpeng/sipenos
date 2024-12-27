<?php

namespace App\Filament\Resources\NomorKeluarResource\Pages;

use App\Filament\Resources\NomorKeluarResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditNomorKeluar extends EditRecord
{
    protected static string $resource = NomorKeluarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
