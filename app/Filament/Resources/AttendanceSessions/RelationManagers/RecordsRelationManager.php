<?php

namespace App\Filament\Resources\AttendanceSessions\RelationManagers;

use Filament\Actions\{CreateAction, DeleteAction, EditAction};
use Filament\Forms\Components\{DateTimePicker, Select, TextInput};
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RecordsRelationManager extends RelationManager
{
    protected static string $relationship = 'records';
    protected static ?string $title = 'Rekam Kehadiran';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('student_id')->relationship('student', 'nim')->searchable()->preload()->required(),
            Select::make('status')->options(['present' => 'Hadir', 'late' => 'Terlambat', 'excused' => 'Izin', 'absent' => 'Alpa'])->required(),
            DateTimePicker::make('check_in_at'), TextInput::make('note')->maxLength(255),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('student.nim')->label('NIM')->searchable(), TextColumn::make('student.user.name')->label('Mahasiswa')->searchable(), TextColumn::make('status')->badge(), TextColumn::make('check_in_at')->dateTime(), TextColumn::make('note')->limit(50),
        ])->headerActions([CreateAction::make()->visible(fn (): bool => $this->getOwnerRecord()->status !== 'closed')])->actions([
            EditAction::make()->visible(fn (): bool => $this->getOwnerRecord()->status !== 'closed'),
            DeleteAction::make()->visible(fn (): bool => $this->getOwnerRecord()->status !== 'closed'),
        ]);
    }
}
