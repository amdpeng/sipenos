<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NomorKeluarResource\Pages;
use App\Filament\Resources\NomorKeluarResource\RelationManagers;
use App\Models\NomorKeluar;
use App\Models\Teacher;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Split;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class NomorKeluarResource extends Resource
{
    protected static ?string $model = NomorKeluar::class;

    protected static ?string $navigationIcon = 'heroicon-o-inbox-stack';
    protected static ?string $navigationLabel = 'Nomor Keluar';
    protected static ?string $navigationGroup = 'Nomor';
    protected static ?int $navigationSort = 2;
    
    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $user = auth()->user(); // Mendapatkan pengguna yang login

        return parent::getEloquentQuery()
            ->when(
                $user->hasRole('Guru'), // Jika pengguna adalah Guru
                fn ($query) => $query->where('created_by', $user->id), // Filter berdasarkan 'created_by'
                fn ($query) => $query // Jika bukan Guru, tampilkan semua data (Super Admin, Admin)
            );
    }
    public static function getCounts(): array
    {
        // Menghitung jumlah berdasarkan tipe surat
        $kurCount = NomorKeluar::where('type_surat', 'KUR')->count();
        $tenCount = NomorKeluar::where('type_surat', 'TEN')->count();
        $umumCount = NomorKeluar::where('type_surat', 'UMUM')->count();
        $pdCount = NomorKeluar::where('type_surat', 'PD')->count();

        return [
            'KUR' => $kurCount,
            'TEN' => $tenCount,
            'UMUM' => $umumCount,
            'PD' => $pdCount,
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Split::make([
                    Section::make([
                        Forms\Components\Select::make('type_surat')
                            ->label('Type Surat')
                            ->options([
                                'KUR' => 'KUR',
                                'TEN' => 'TEN',
                                'UMUM' => 'UMUM',
                                'PD' => 'PD',
                            ])
                            ->columns(2)
                            ->required(),
                        DatePicker::make('tanggal_surat')
                            ->label('Tanggal Surat')
                            ->columns(2)
                            ->displayFormat('d/m/Y') // Format tampilan
                            ->format('Y-m-d'), 
                        Forms\Components\TextInput::make('nomor_surat')
                            ->label('Nomor Surat')
                            ->disabled()
                            ->columnSpanFull()
                            ->hidden(fn (Forms\Get $get) => $get('id') === null), // Nomor surat diisi otomatis,
                        TextInput::make('tahun')
                            ->label('Tahun')
                            ->hidden()
                            ->columnSpanFull()
                            ->default(now()->year) // Isi tahun saat ini
                            ->disabled(), // Tidak dapat diubah oleh pengguna
                        Textarea::make('keterangan_surat')
                            ->label('Keterangan Surat')
                            ->columnSpanFull()
                            ->maxLength(255), 
                        Select::make('nama_guru')
                            ->label('Nama Guru Yang Melakukan SPT')
                            ->multiple()
                            ->columnSpanFull()
                            ->getSearchResultsUsing(fn(string $search): array =>
                            Teacher::where('teacher_name', 'like', "%{$search}%")->limit(50)->pluck('teacher_name', 'id')->toArray())
                            ->getOptionLabelsUsing(fn(array $values): array =>
                                Teacher::whereIn('id', $values)->pluck('teacher_name', 'id')->toArray())
                            ->searchable(),
                    ])->columns(2),
                ])->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('type_surat')
                    ->label('Type')
                    ->disabledClick()
                    ->color(function ($state) {
                        // Memberikan warna berdasarkan type surat
                        switch ($state) {
                            case 'KUR':
                                return 'success';  // Warna hijau untuk KUR
                            case 'TEN':
                                return 'primary';  // Warna biru untuk TEN
                                case 'UMUM':
                                    return 'green';  // Warna biru untuk TEN
                            case 'PD':
                                return 'danger';  // Warna merah untuk PD
                            default:
                                return 'gray';    // Warna abu-abu jika type tidak dikenali
                        }
                    })
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('nomor_surat')
                    ->label('Nomor Surat')
                    ->disabledClick()
                    ->copyable()
                    ->color(function ($record) {
                        // Mendapatkan type surat dari record yang sama
                        $typeSurat = $record->type_surat;
                        
                        // Memberikan warna nomor surat berdasarkan type surat
                        switch ($typeSurat) {
                            case 'KUR':
                                return 'success';  // Warna hijau untuk KUR
                            case 'TEN':
                                return 'primary';  // Warna biru untuk TEN
                                case 'UMUM':
                                    return 'green';  // Warna biru untuk TEN
                            case 'PD':
                                return 'danger';  // Warna merah untuk PD
                            default:
                                return 'gray';    // Warna abu-abu jika type tidak dikenali
                        }
                    })
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('tanggal_surat')
                    ->label('Tanggal')
                    ->disabledClick()
                    ->sortable()
                    ->formatStateUsing(fn ($state) => \Carbon\Carbon::parse($state)->format('d-m-Y'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('keterangan_surat')
                    ->label('Keterangan')
                    ->disabledClick()
                    ->wrap()
                    ->limit(15)
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('nama_guru')
                    ->label('Yang melakukan SPT')
                    ->disabledClick()
                    ->sortable()
                    ->listWithLineBreaks()
                    ->bulleted()
                    ->disabledClick()
                    ->formatStateUsing(fn($state) => Teacher::find($state)?->teacher_name)
                    ->searchable(),
                IconColumn::make('status')
                    ->label('Status')
                    ->options([
                        'heroicon-o-check-circle' => fn($state) => $state === 'Validated',
                        'heroicon-o-arrow-path-rounded-square' => fn($state) => $state !== 'Validated',
                    ])
                    ->colors([
                        'success' => fn($state) => $state === 'Validated',
                        'danger' => fn($state) => $state !== 'Validated',
                    ])
                    ->tooltip(fn($state) => $state === 'Validated' ? 'Validated' : 'Pending'),
                TextColumn::make('creator.name')
                    ->label('created_by')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->visible(fn () => Gate::allows('reorder_nomor::keluar'))
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('created_at')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->dateTime(),
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
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                BulkAction::make('Change Status')
                ->icon('heroicon-m-check')
                ->requiresConfirmation()
                ->visible(fn () => auth()->user()->can('status_nomor::keluar'))
                ->form([
                    Select::make('status')
                        ->label('status')
                        ->options([
                            'Validated' => 'Validated',
                            'Pending' => 'Pending',
                        ])
                        ->required(),
                ])
                ->action(function (Collection $records, array $data) {
                    $records->each(function ($record) use ($data) {
                        NomorKeluar::where('id', $record->id)->update(['status' => $data['status']]);
                    });
                }),
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
            ]),
            ]);
    }
    protected function beforeCreate(array $data): array
    {
        $data['nomor_surat'] = NomorKeluar::generateNomorSurat($data['type_surat']);
        return $data;
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
            'index' => Pages\ListNomorKeluars::route('/'),
            'create' => Pages\CreateNomorKeluar::route('/create'),
            'edit' => Pages\EditNomorKeluar::route('/{record}/edit'),
        ];
    }
}
