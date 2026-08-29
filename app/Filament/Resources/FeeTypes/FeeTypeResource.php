<?php

namespace App\Filament\Resources\FeeTypes;

use App\Models\FeeType;
use App\Filament\Resources\FeeTypes\Pages;
use Filament\Forms\Components\{Select, TextInput};
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\{Columns\TextColumn, Table};
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use BackedEnum;
use UnitEnum;

class FeeTypeResource extends Resource
{
    protected static ?string $slug = 'fee-types';
    protected static ?string $model = FeeType::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;
    protected static string|UnitEnum|null $navigationGroup = 'Keuangan';
    protected static ?string $navigationLabel = 'Jenis Biaya';
    protected static ?string $modelLabel = 'Jenis Biaya';
    protected static ?string $pluralModelLabel = 'Jenis Biaya';
    public static function form(Schema $schema): Schema
    {
        return $schema->components([TextInput::make('code')->label('Kode')->required()->maxLength(50)->unique(ignoreRecord: true), TextInput::make('name')->label('Nama Biaya')->required()->maxLength(150), TextInput::make('default_amount')->label('Nominal Default')->numeric()->minValue(0)->required(), Select::make('frequency')->label('Frekuensi')->options(['one_time' => 'Sekali Bayar', 'per_semester' => 'Per Semester', 'per_year' => 'Per Tahun'])->default('per_semester')->required(), Select::make('is_active')->label('Status')->options([1 => 'Aktif', 0 => 'Tidak Aktif'])->default(1)->required()]);
    }
    public static function table(Table $table): Table
    {
        return $table->columns([TextColumn::make('code')->label('Kode')->searchable(), TextColumn::make('name')->label('Nama'), TextColumn::make('default_amount')->label('Nominal')->money('IDR'), TextColumn::make('frequency')->label('Frekuensi')->badge(), TextColumn::make('is_active')->label('Status')->formatStateUsing(fn($s) => $s ? 'Aktif' : 'Tidak Aktif')->badge()])->actions([EditAction::make()->label('Ubah'), DeleteAction::make()->label('Hapus')->requiresConfirmation()]);
    }
    public static function getPages(): array
    {
        return ['index' => Pages\ListFeeTypes::route('/'), 'create' => Pages\CreateFeeType::route('/create'), 'edit' => Pages\EditFeeType::route('/{record}/edit')];
    }
}
