<?php

namespace App\Filament\Resources\NomorKeluarResource\Widgets;

use App\Filament\Resources\NomorKeluarResource;
use App\Filament\Resources\NomorMasukResource;
use App\Models\NomorKeluar;
use App\Models\NomorMasuk;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Widgets\StatsOverviewWidget\Stat;

class NomorStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $kurCount = NomorKeluar::where('type_surat', 'KUR')->count();
        $tenCount = NomorKeluar::where('type_surat', 'TEN')->count();
        $umumCount = NomorKeluar::where('type_surat', 'UMUM')->count();
        $pdCount = NomorKeluar::where('type_surat', 'PD')->count();

        return [
            Card::make('KUR', $kurCount)
                ->description('Jumlah surat')
                ->url(NomorKeluarResource::getUrl('index'))
                ->color('success'),  // Warna hijau untuk KUR
            Card::make('TEN', $tenCount)    
                ->description('Jumlah surat')
                ->url(NomorKeluarResource::getUrl('index'))
                ->color('warning'), // Warna kuning untuk TEN
            Card::make('UMUM', $umumCount)    
                ->description('Jumlah surat')
                ->url(NomorKeluarResource::getUrl('index'))
                ->color('primary'), // Warna kuning untuk TEN
            Card::make('PD', $pdCount)
                ->description('Jumlah surat')
                ->url(NomorKeluarResource::getUrl('index'))
                ->color('danger'), // Warna merah untuk PD
        ];
    }
    protected function getStyles(): array
    {
        return [
            'class' => 'flex justify-center items-center gap-4 h-screen',  // Menambahkan kelas Tailwind CSS
        ];
    }
}
