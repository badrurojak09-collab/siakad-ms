<?php
namespace App\Filament\Resources\AdmissionPeriods;

use App\Filament\Resources\AdmissionPeriods\Pages;
use App\Models\AdmissionPeriod;
use Filament\Forms\Components\{DatePicker, Select, TextInput, Textarea};
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\{Columns\TextColumn, Table};
use Filament\{Actions\DeleteAction, Actions\EditAction};
use BackedEnum;
use UnitEnum;

class AdmissionPeriodResource extends Resource
{
    protected static ?string $slug = 'admission-periods';
    protected static ?string $model = AdmissionPeriod::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;
    protected static string|UnitEnum|null $navigationGroup = 'Penerimaan Mahasiswa Baru';
    protected static ?string $navigationLabel = 'Periode PMB';
    protected static ?string $modelLabel = 'Periode PMB';
    protected static ?string $pluralModelLabel = 'Periode PMB';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([TextInput::make('code')->label('Kode')->required()->maxLength(50)->unique(ignoreRecord: true), TextInput::make('name')->label('Nama Periode')->required()->maxLength(150), DatePicker::make('registration_start')->label('Mulai Pendaftaran')->required(), DatePicker::make('registration_end')->label('Akhir Pendaftaran')->afterOrEqual('registration_start')->required(), DatePicker::make('selection_end')->label('Akhir Seleksi')->afterOrEqual('registration_end'), Select::make('status')->label('Status')->options(['draft' => 'Draf', 'open' => 'Dibuka', 'closed' => 'Ditutup'])->default('draft')->required(), Textarea::make('requirements')->label('Persyaratan JSON')->helperText('Isi konfigurasi persyaratan dalam format JSON.')->json()]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([TextColumn::make('code')->label('Kode')->searchable(), TextColumn::make('name')->label('Nama'), TextColumn::make('registration_start')->label('Mulai')->date(), TextColumn::make('registration_end')->label('Selesai')->date(), TextColumn::make('status')->label('Status')->badge(), TextColumn::make('applicants_count')->counts('applicants')->label('Pendaftar')])->actions([EditAction::make()->label('Ubah')->visible(fn(AdmissionPeriod $r) => $r->status !== 'closed'), DeleteAction::make()->label('Hapus')->visible(fn(AdmissionPeriod $r) => $r->status === 'draft')->requiresConfirmation()]);
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ListAdmissionPeriods::route('/'), 'create' => Pages\CreateAdmissionPeriod::route('/create'), 'edit' => Pages\EditAdmissionPeriod::route('/{record}/edit')];
    }
}
