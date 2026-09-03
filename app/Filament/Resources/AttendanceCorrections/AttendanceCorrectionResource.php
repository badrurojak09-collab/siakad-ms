<?php
namespace App\Filament\Resources\AttendanceCorrections;

use App\Filament\Resources\AttendanceCorrections\Pages;
use App\Models\AttendanceCorrection;
use Filament\Forms\Components\{Select, Textarea};
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\{Columns\TextColumn, Table};
use Filament\{Actions\DeleteAction, Actions\EditAction};
use BackedEnum;
use UnitEnum;

class AttendanceCorrectionResource extends Resource
{
    protected static ?string $slug = 'attendance-corrections';
    protected static ?string $model = AttendanceCorrection::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPencilSquare;
    protected static string|UnitEnum|null $navigationGroup = 'Presensi';
    protected static ?string $navigationLabel = 'Koreksi Presensi';
    protected static ?string $modelLabel = 'Koreksi Presensi';
    protected static ?string $pluralModelLabel = 'Koreksi Presensi';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([Select::make('attendance_record_id')->label('Rekaman Presensi')->relationship('attendanceRecord', 'id')->searchable()->preload()->required(), Select::make('requested_by')->label('Diajukan Oleh')->relationship('requestedBy', 'name')->searchable()->preload()->required(), Textarea::make('reason')->label('Alasan Koreksi')->required()->columnSpanFull(), Select::make('status')->label('Status')->options(['pending' => 'Menunggu', 'approved' => 'Disetujui', 'rejected' => 'Ditolak'])->default('pending')->required(), Select::make('approved_by')->label('Disetujui Oleh')->relationship('approvedBy', 'name')->searchable()->preload()->nullable()]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([TextColumn::make('attendanceRecord.student.nim')->label('NIM'), TextColumn::make('requestedBy.name')->label('Pengaju'), TextColumn::make('reason')->label('Alasan')->limit(50), TextColumn::make('status')->label('Status')->badge(), TextColumn::make('approvedBy.name')->label('Penyetuju')->placeholder('-')])->actions([EditAction::make()->label('Ubah')->visible(fn(AttendanceCorrection $r) => $r->status === 'pending'), DeleteAction::make()->label('Hapus')->visible(fn(AttendanceCorrection $r) => $r->status === 'pending')->requiresConfirmation()]);
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ListAttendanceCorrections::route('/'), 'create' => Pages\CreateAttendanceCorrection::route('/create'), 'edit' => Pages\EditAttendanceCorrection::route('/{record}/edit')];
    }
}
