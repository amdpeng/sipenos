<?php

namespace App\Filament\Resources\NomorKeluarResource\Pages;

use App\Filament\Resources\NomorKeluarResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use Maatwebsite\Excel\Facades\Excel;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use pxlrbt\FilamentExcel\Actions\Pages\ExportAction;


class ListNomorKeluars extends ListRecords
{
    protected static string $resource = NomorKeluarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Create New'),
                ExportAction::make() 
                ->visible(fn () => auth()->user()->can('exports_nomor::keluar'))
                    ->exports([
                        ExcelExport::make()
                            ->fromTable()
                            ->withFilename(fn ($resource) => $resource::getModelLabel() . '-' . date('Y-m-d'))
                            ->withWriterType(\Maatwebsite\Excel\Excel::XLSX)
                    ]), 
        ];
    }
}
