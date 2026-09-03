<?php
namespace App\Filament\Resources\AttendanceSessions;

use App\Filament\Resources\AttendanceSessions\Pages;
use App\Models\AttendanceSession;
use Filament\Forms\Components\{DatePicker, Select, TextInput};
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\{Columns\TextColumn, Table};
use Filament\{Actions\DeleteAction, Actions\EditAction};
use BackedEnum;
use UnitEnum;

class AttendanceSessionResource extends Resource
{
    protected static ?string $slug = 'attendance-sessions';
    protected static ?string $model = AttendanceSession::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;
    protected static string|UnitEnum|null $navigationGroup = 'Presensi';
    protected static ?string $navigationLabel = 'Sesi Presensi';
    protected static ?string $modelLabel = 'Sesi Presensi';
    protected static ?string $pluralModelLabel = 'Sesi Presensi';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([Select::make('course_class_id')->label('Kelas Perkuliahan')->relationship('courseClass', 'class_code')->searchable()->preload()->required(), DatePicker::make('meeting_date')->label('Tanggal Pertemuan')->required()->native(false), TextInput::make('meeting_number')->label('Pertemuan Ke-')->numeric()->minValue(1)->maxValue(32)->required(), TextInput::make('topic')->label('Topik')->maxLength(255)]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([TextColumn::make('courseClass.class_code')->label('Kelas'), TextColumn::make('courseClass.course.name')->label('Mata Kuliah'), TextColumn::make('meeting_date')->label('Tanggal')->date('d M Y'), TextColumn::make('meeting_number')->label('Pertemuan Ke-')->sortable(), TextColumn::make('topic')->label('Topik')->searchable(), TextColumn::make('opened_at')->label('Dibuka')->dateTime('d M Y H:i'), TextColumn::make('closed_at')->label('Ditutup')->dateTime('d M Y H:i')])->actions([EditAction::make()->label('Ubah')->visible(fn(AttendanceSession $r) => $r->closed_at === null), DeleteAction::make()->label('Hapus')->visible(fn(AttendanceSession $r) => $r->opened_at === null)->requiresConfirmation()]);
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ListAttendanceSessions::route('/'), 'create' => Pages\CreateAttendanceSession::route('/create'), 'edit' => Pages\EditAttendanceSession::route('/{record}/edit')];
    }
}
