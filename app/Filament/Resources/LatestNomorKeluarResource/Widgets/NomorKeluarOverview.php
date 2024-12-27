<?php

namespace App\Filament\Resources\LatestNomorKeluarResource\Widgets;

use App\Filament\Resources\NomorKeluarResource;
use App\Models\NomorKeluar;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Widgets\TableWidget as BaseWidget;

class NomorKeluarOverview extends BaseWidget
{
    protected int | string | array $columnSpan = ''; // Lebar widget di dashboard
    protected static ?int $sort = 2; // Urutan widget di dashboard

    public function table(Table $table): Table
    {

        return $table
           ->query(NomorKeluar::query()->latest('created_at')) // Query untuk data surat keluar terbaru
            ->defaultPaginationPageOption(2) // Pagination default
            ->defaultSort('created_at', 'desc') // Sorting default
            ->columns([
                // Kolom Nomor Surat
                TextColumn::make('type_surat')
                    ->label('Type')
                    ->disabledClick(),

                // Kolom Nomor Surat
                TextColumn::make('nomor_surat')
                    ->label('Nomor Surat')
                    ->disabledClick(),

                //Kolom Tanggal Surat
                TextColumn::make('tanggal_surat')
                    ->label('Tanggal Surat')
                    ->date('d F Y')
                    ->disabledClick(),
            ])
            ->filters([
                SelectFilter::make('type_surat')
                    ->label('Jenis Surat')
                    ->multiple()
                    ->options([
                        'KUR' => 'KUR',
                        'TEN' => 'TEN',
                        'UMUM' => 'UMUM',
                        'PD' => 'PD',
                    ]),
            ])
            ->actions([
                // Aksi untuk membuka detail surat
                Tables\Actions\Action::make('open')
                    ->url(fn (NomorKeluar $record): string => NomorKeluarResource::getUrl('edit', ['record' => $record])),
            ]);
    }
}
