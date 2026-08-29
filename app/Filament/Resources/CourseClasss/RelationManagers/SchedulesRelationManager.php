<?php

namespace App\Filament\Resources\CourseClasss\RelationManagers;

use Filament\Forms\Components\{Select, TextInput, TimePicker, Toggle};
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\{Actions\CreateAction, Actions\DeleteAction, Actions\EditAction, Columns\TextColumn, Table};

class SchedulesRelationManager extends RelationManager
{
    protected static string $relationship = 'schedules';
    protected static ?string $title = 'Jadwal Kelas';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('day_of_week')->label('Hari')->options([1=>'Senin',2=>'Selasa',3=>'Rabu',4=>'Kamis',5=>'Jumat',6=>'Sabtu',7=>'Minggu'])->required(),
            TimePicker::make('start_time')->label('Mulai')->seconds(false)->required(),
            TimePicker::make('end_time')->label('Selesai')->seconds(false)->after('start_time')->required(),
            Select::make('room_id')->label('Ruang')->relationship('room', 'name')->searchable()->preload()->nullable(),
            Select::make('lecturer_id')->label('Dosen')->relationship('lecturer', 'nidn')->searchable()->preload()->nullable(),
            TextInput::make('week_number')->label('Minggu Ke-')->numeric()->minValue(1)->maxValue(52),
            Toggle::make('is_online')->label('Daring')->default(false),
            TextInput::make('meeting_url')->label('URL Pertemuan')->url()->nullable(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('day_of_week')->label('Hari')->formatStateUsing(fn ($state) => [1=>'Senin',2=>'Selasa',3=>'Rabu',4=>'Kamis',5=>'Jumat',6=>'Sabtu',7=>'Minggu'][$state] ?? $state),
            TextColumn::make('start_time')->label('Mulai'), TextColumn::make('end_time')->label('Selesai'),
            TextColumn::make('room.name')->label('Ruang')->placeholder('Daring'), TextColumn::make('lecturer.nidn')->label('Dosen'),
        ])->headerActions([CreateAction::make()->label('Tambah Jadwal')])->actions([EditAction::make()->label('Ubah'), DeleteAction::make()->label('Hapus')->requiresConfirmation()]);
    }
}
