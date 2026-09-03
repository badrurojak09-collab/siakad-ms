<?php
namespace App\Filament\Resources\Applicants;

use App\Filament\Resources\Applicants\Pages;
use App\Models\Applicant;
use Filament\Forms\Components\{DateTimePicker, Select, TextInput};
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\{Columns\TextColumn, Table};
use Filament\{Actions\DeleteAction, Actions\EditAction};
use BackedEnum;
use UnitEnum;

class ApplicantResource extends Resource
{
    protected static ?string $slug = 'applicants';
    protected static ?string $model = Applicant::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserPlus;
    protected static string|UnitEnum|null $navigationGroup = 'Penerimaan Mahasiswa Baru';
    protected static ?string $navigationLabel = 'Pendaftar PMB';
    protected static ?string $modelLabel = 'Pendaftar PMB';
    protected static ?string $pluralModelLabel = 'Pendaftar PMB';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([Select::make('admission_period_id')->label('Periode PMB')->relationship('period', 'name')->searchable()->preload()->required(), TextInput::make('registration_number')->label('Nomor Pendaftaran')->required()->maxLength(80)->unique(ignoreRecord: true), TextInput::make('full_name')->label('Nama Lengkap')->required()->maxLength(150), TextInput::make('email')->label('Surel')->email()->required(), TextInput::make('phone')->label('Telepon')->maxLength(30), TextInput::make('identity_number')->label('Nomor Identitas')->maxLength(50), TextInput::make('school_origin')->label('Asal Sekolah')->maxLength(150), TextInput::make('selection_score')->label('Nilai Seleksi')->numeric()->minValue(0)->maxValue(100), Select::make('status')->label('Status')->options(['draft' => 'Draf', 'submitted' => 'Diajukan', 'under_review' => 'Ditinjau', 'selection_passed' => 'Lulus Seleksi', 'selection_failed' => 'Tidak Lulus', 'converted' => 'Dikonversi'])->default('draft')->required(), DateTimePicker::make('submitted_at')->label('Diajukan Pada')->native(false)]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([TextColumn::make('registration_number')->label('Nomor')->searchable(), TextColumn::make('full_name')->label('Nama')->searchable(), TextColumn::make('period.name')->label('Periode'), TextColumn::make('email')->label('Surel'), TextColumn::make('selection_score')->label('Nilai Seleksi'), TextColumn::make('status')->label('Status')->badge(), TextColumn::make('submitted_at')->label('Diajukan Pada')->dateTime('d M Y H:i')])->actions([EditAction::make()->label('Ubah')->visible(fn(Applicant $r) => !in_array($r->status, ['converted'], true)), DeleteAction::make()->label('Hapus')->visible(fn(Applicant $r) => $r->status === 'draft')->requiresConfirmation()]);
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ListApplicants::route('/'), 'create' => Pages\CreateApplicant::route('/create'), 'edit' => Pages\EditApplicant::route('/{record}/edit')];
    }
}
