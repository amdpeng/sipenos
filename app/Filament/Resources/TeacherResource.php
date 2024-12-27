<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TeacherResource\Pages;
use App\Filament\Resources\TeacherResource\RelationManagers;
use App\Models\Teacher;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Split;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TeacherResource extends Resource
{
    protected static ?string $model = Teacher::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationLabel = 'Teachers';
    protected static ?string $navigationGroup = 'Data PTK';
    protected static ?int $navigationSort = 2;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Split::make([
                    Section::make([
                        TextInput::make('nip_nuptk')
                            ->label('NIP/NUPTK')
                            ->required()
                            ->maxLength(20),
                        TextInput::make('teacher_name')
                            ->label('Nama Lengkap')
                            ->required(),
                        Select::make('gender')
                            ->label('Jenis Kelamin')
                            ->options([
                                'L' => 'L',
                                'P' => 'P',
                            ])->required(),
                        Select::make('religion')
                            ->label('Agama')
                            ->options([
                                'Islam' => 'Islam',
                                'Kristen' => 'Kristen',
                                'Khatolik' => 'Khatolik',
                                'Protestan' => 'Protestan',
                                'Hindu' => 'Hindu',
                                'Buddha' => 'Buddha',
                                'Khonghucu' => 'Khonghucu',
                            ])
                            ->searchable()
                            ->required(),
                        DatePicker::make('date_of_birth')
                            ->label('Tanggal Lahir')
                            ->required()
                            ->displayFormat('d/m/Y')
                            ->maxDate(now()),
                        Select::make('jenis_ptk')
                            ->label('Jenis PTK')
                            ->searchable()
                            ->options([
                                'Kepala Sekolah' => 'Kepala Sekolah',
                                'Guru Mapel' => 'Guru Mapel',
                                'Tenaga Kependidikan' => 'Tenaga Kependidikan',
                            ])
                            ->required(),
                        Select::make('type')
                            ->label('Type')
                            ->searchable()
                            ->options([
                                'ASN' => 'ASN',
                                'P3K' => 'P3K',
                                'Honorer' => 'Honorer',
                            ])
                            ->required(),
                        TextInput::make('whatsapp_number')
                            ->label('Nomor Whatsapp')
                            ->tel()
                            ->maxLength('13')
                            ->required(),
                    ])->columns(2),
                ])->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nip_nuptk')
                    ->icon('heroicon-m-identification') 
                    ->label('NIP/NUPTK')
                    ->searchable(),
                TextColumn::make('teacher_name')
                    ->icon('heroicon-m-identification')
                    ->label('Nama Guru')
                    ->searchable(),
                    // ->searchable(isIndividual: true, isGlobal: false)
                TextColumn::make('gender')
                    ->icon('heroicon-m-identification') 
                    ->label('L/P')
                    ->searchable(),
                TextColumn::make('religion')
                    ->icon('heroicon-m-identification') 
                    ->label('Agama')
                    ->searchable(),
                TextColumn::make('date_of_birth')
                    ->icon('heroicon-m-calendar-days')
                    ->label('Tanggal Lahir')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('jenis_ptk')
                    ->icon('heroicon-m-identification') 
                    ->label('Jenis PTK')
                    ->searchable(),
                TextColumn::make('type')
                    ->icon('heroicon-m-identification') 
                    ->label('Type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('whatsapp_number')
                    ->icon('heroicon-m-phone')
                    ->label('No Whatsapp')
                    ->searchable(),
            ])
            ->filters([
                SelectFilter::make('jenis_ptk')
                    ->label('Jenis PTK')
                    ->multiple()
                    ->options([
                        'Kepala Sekolah'=>'Kepala Sekolah',
                        'Guru Mapel'=>'Guru Mapel',
                        'Tenaga Kependidikan'=>'Tenaga Kependidikan',
                    ]),
                SelectFilter::make('type')
                    ->label('Type')
                    ->multiple()
                    ->options([
                        'ASN' => 'ASN',
                        'P3K' => 'P3K',
                        'Honorer' => 'Honorer',
                    ]),
                SelectFilter::make('religion')
                    ->label('Agama')
                    ->multiple()
                    ->options([
                        'Islam' => 'Islam',
                        'Kristen' => 'Kristen',
                        'Khatolik' => 'Khatolik',
                        'Protestan' => 'Protestan',
                        'Hindu' => 'Hindu',
                        'Buddha' => 'Buddha',
                        'Khonghucu' => 'Khonghucu',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListTeachers::route('/'),
            'create' => Pages\CreateTeacher::route('/create'),
            'edit' => Pages\EditTeacher::route('/{record}/edit'),
        ];
    }
}
