<?php
namespace App\Filament\Resources\Schedules;

use App\Filament\Resources\Schedules\Pages;
use App\Models\Schedule;
use Filament\Forms\Components\{Select, TextInput, TimePicker, Toggle};
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\{Columns\TextColumn, Table};
use Filament\{Actions\DeleteAction, Actions\EditAction};
use BackedEnum;
use UnitEnum;

class ScheduleResource extends Resource
{
    protected static ?string $slug = 'schedules';
    protected static ?string $model = Schedule::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;
    protected static string|UnitEnum|null $navigationGroup = 'Penjadwalan & Ruang';
    protected static ?string $navigationLabel = 'Jadwal';
    protected static ?string $modelLabel = 'Jadwal';
    protected static ?string $pluralModelLabel = 'Jadwal';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([Select::make('course_class_id')->label('Kelas Perkuliahan')->relationship('courseClass', 'class_code')->searchable()->preload()->required(), Select::make('day_of_week')->label('Hari')->options([1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu', 7 => 'Minggu'])->required(), TimePicker::make('start_time')->label('Mulai')->seconds(false)->required(), TimePicker::make('end_time')->label('Selesai')->seconds(false)->after('start_time')->required(), Select::make('room_id')->label('Ruang')->relationship('room', 'name')->searchable()->preload()->nullable(), Select::make('lecturer_id')->label('Dosen')->relationship('lecturer', 'nidn')->searchable()->preload()->nullable(), TextInput::make('week_number')->label('Minggu Ke-')->numeric()->minValue(1)->maxValue(52), Toggle::make('is_online')->label('Perkuliahan Daring')->default(false), TextInput::make('meeting_url')->label('URL Pertemuan')->url()->nullable()]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([TextColumn::make('courseClass.class_code')->label('Kelas'), TextColumn::make('courseClass.course.name')->label('Mata Kuliah'), TextColumn::make('day_of_week')->label('Hari')->formatStateUsing(fn($s) => [1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu', 7 => 'Minggu'][$s] ?? $s), TextColumn::make('start_time')->label('Mulai'), TextColumn::make('end_time')->label('Selesai'), TextColumn::make('room.name')->label('Ruang')->placeholder('Daring'), TextColumn::make('lecturer.nidn')->label('Dosen')])->actions([EditAction::make()->label('Ubah'), DeleteAction::make()->label('Hapus')->requiresConfirmation()]);
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ListSchedules::route('/'), 'create' => Pages\CreateSchedule::route('/create'), 'edit' => Pages\EditSchedule::route('/{record}/edit')];
    }
}
