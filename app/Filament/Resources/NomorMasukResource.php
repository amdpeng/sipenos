<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NomorMasukResource\Pages;
use App\Filament\Resources\NomorMasukResource\RelationManagers;
use App\Models\NomorMasuk;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Split;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class NomorMasukResource extends Resource
{
    protected static ?string $model = NomorMasuk::class;

    protected static ?string $navigationIcon = 'heroicon-o-document';
    protected static ?string $navigationLabel = 'Nomor Masuk';
    protected static ?string $navigationGroup = 'Nomor';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Split::make([
                    Section::make([
                        // Nomor Surat
                        Forms\Components\TextInput::make('nomor_surat')
                            ->label('Nomor Surat')
                            ->placeholder('001/SMKN-/VII/2024')
                            ->required()
                            ->maxLength(255),

                        // Tanggal Surat
                        Forms\Components\DatePicker::make('tanggal_surat')
                            ->label('Tanggal Surat')
                            ->required(),

                        // Pengirim Surat
                        Forms\Components\TextInput::make('pengirim')
                            ->label('Pengirim Surat')
                            ->placeholder('George Madhison')
                            ->required()
                            ->maxLength(255),

                        // Satuan Pendidikan (misalnya nama sekolah)
                        Forms\Components\Select::make('satuan_pendidikan')
                            ->label('Satuan Pendidikan')
                            ->options([
                                'SD' => 'Sekolah Dasar',
                                'SMP' => 'Sekolah Menengah Pertama',
                                'SMA' => 'Sekolah Menengah Atas',
                                'SMK' => 'Sekolah Menengah Kejuruan',
                                'Perguruan Tinggi' => 'Perguruan Tinggi',
                                'Perangkat Desa' => 'Perangkat Desa',
                            ])
                            ->required(),

                        // Keterangan Surat
                        Forms\Components\Textarea::make('keterangan_surat')
                            ->label('Keterangan Surat')
                            ->columnSpanFull()
                            ->maxLength(500),

                        // File Surat (jika ada lampiran)
                        Forms\Components\FileUpload::make('file_surat')
                            ->label('Lampiran Surat')
                            ->disk('public')
                            ->directory('surat_masuk')
                            ->nullable()
                            ->columnSpanFull(),
                    ])->columns(2),
                ])->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nomor_surat')
                    ->searchable()
                    ->disabledClick()
                    ->label('Nomor Surat'),
                Tables\Columns\TextColumn::make('tanggal_surat')
                    ->searchable()
                    ->disabledClick()
                    ->label('Tanggal Surat'),
                Tables\Columns\TextColumn::make('pengirim')
                    ->searchable()
                    ->label('Nama Pengirim')
                    ->disabledClick(),
                Tables\Columns\TextColumn::make('satuan_pendidikan')
                    ->searchable()
                    ->disabledClick()
                    ->label('Pendidikan'),
                Tables\Columns\TextColumn::make('keterangan_surat')
                    ->searchable()
                    ->disabledClick()
                    ->wrap()
                    ->limit(15)
                    ->label('Keterangan'),
                IconColumn::make('status')
                    ->searchable()
                    ->disabledClick()
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
                Tables\Filters\SelectFilter::make('satuan_pendidikan')
                ->options([
                    'SD' => 'Sekolah Dasar',
                    'SMP' => 'Sekolah Menengah Pertama',
                    'SMA' => 'Sekolah Menengah Atas',
                    'SMK' => 'Sekolah Menengah Kejuruan',
                    'Perguruan Tinggi' => 'Perguruan Tinggi',
                    'Perangkat Desa' => 'Perangkat Desa',
                ])
                ->label('Satuan Pendidikan'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                BulkAction::make('Change Status')
                ->icon('heroicon-m-check')
                ->requiresConfirmation()
                ->form([
                    Select::make('status')
                        ->label('status')
                        ->options([
                            'Diterima' => 'Diterima',
                            'Belum Diterima' => 'Belum Diterima',
                        ])
                        ->required(),
                ])
                ->action(function (Collection $records, array $data) {
                    $records->each(function ($record) use ($data) {
                        NomorMasuk::where('id', $record->id)->update(['status' => $data['status']]);
                    });
                }),
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
    public static function navigation(): array
    {
        return [
            self::make()
                ->label('Surat Masuk')
                ->icon('heroicon-o-document')
                ->group('Satuan Pendidikan'),
        ];
    }
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNomorMasuks::route('/'),
            'create' => Pages\CreateNomorMasuk::route('/create'),
            'edit' => Pages\EditNomorMasuk::route('/{record}/edit'),
        ];
    }
}
