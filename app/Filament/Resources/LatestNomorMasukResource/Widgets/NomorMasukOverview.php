<?php

namespace App\Filament\Resources\LatestNomorMasukResource\Widgets;

use App\Filament\Resources\NomorMasukResource;
use App\Models\NomorMasuk;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Widgets\TableWidget as BaseWidget;

class NomorMasukOverview extends BaseWidget
{
    protected int | string | array $columnSpan = ''; // Lebar widget di dashboard
    protected static ?int $sort = 3; // Urutan widget di dashboard

    public function table(Table $table): Table
    {
        return $table
        ->query(NomorMasuk::query()->latest('created_at')) // Query untuk data surat keluar terbaru
        ->defaultPaginationPageOption(2) // Pagination default
        ->defaultSort('created_at', 'desc') // Sorting default
        ->columns([
            // Kolom Nomor Surat
            TextColumn::make('nomor_surat')
                ->label('Nomor Surat')
                ->disabledClick(),

            //Kolom Tanggal Surat
            TextColumn::make('tanggal_surat')
                ->label('Tanggal Surat')
                ->date('d F Y')
                ->disabledClick(),
                IconColumn::make('status')
                ->label('Status')
                ->options([
                    'heroicon-o-check-circle' => fn($state) => $state === 'Diterima',
                    'heroicon-o-arrow-path-rounded-square' => fn($state) => $state !== 'Diterima',
                ])
                ->colors([
                    'success' => fn($state) => $state === 'Diterima',
                    'danger' => fn($state) => $state !== 'Diterima',
                ])
                ->tooltip(fn($state) => $state === 'Diterima' ? 'Diterima' : 'Belum Diterima'),
        ])
        ->filters([
            // Filter Berdasarkan Status
            SelectFilter::make('status')
                ->label('Status')
                ->options([
                    'Belum Diterima' => 'Belum Diterima',
                    'Diterima' => 'Diterima',
                ]),
        ])
        ->actions([
            // Aksi untuk membuka detail surat
            Tables\Actions\Action::make('open')
                ->url(fn (NomorMasuk $record): string => NomorMasukResource::getUrl('edit', ['record' => $record])),
        ]);
    }
}
