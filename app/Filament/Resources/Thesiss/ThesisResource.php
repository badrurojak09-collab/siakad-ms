<?php

namespace App\Filament\Resources\Thesiss;

use App\Filament\Resources\Concerns\ScopesOwnStudentRecords;
use App\Filament\Resources\Thesiss\RelationManagers\SupervisionsRelationManager;
use App\Models\Thesis;
use Filament\Forms\Components\{DatePicker, DateTimePicker, Select, TextInput, Textarea};
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\{Actions\DeleteAction, Actions\EditAction};
use BackedEnum;
use UnitEnum;

class ThesisResource extends Resource
{
    use ScopesOwnStudentRecords;

    protected static ?string $model = Thesis::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static string|UnitEnum|null $navigationGroup = 'Tugas Akhir';
    protected static ?string $navigationLabel = 'Tugas Akhir';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('student_id')->relationship('student', 'nim')->searchable()->preload()->required(),
            TextInput::make('title')->required()->maxLength(255),
            Textarea::make('abstract')->columnSpanFull(),
            Select::make('thesis_type')->options(['skripsi' => 'Skripsi', 'tesis' => 'Tesis', 'disertasi' => 'Disertasi'])->required(),
            Select::make('supervisor_1_id')->relationship('supervisor1', 'nidn')->searchable()->preload()->required(),
            Select::make('supervisor_2_id')->relationship('supervisor2', 'nidn')->searchable()->preload()->nullable(),
            DatePicker::make('proposed_date'),
            Select::make('status')->options(['proposed' => 'Proposed', 'in_progress' => 'In Progress', 'defense_scheduled' => 'Defense Scheduled', 'completed' => 'Selesai', 'cancelled' => 'Dibatalkan'])->disabled(),
            DateTimePicker::make('defense_date')->disabled(),
            TextInput::make('defense_room')->maxLength(100)->disabled(),
            TextInput::make('final_document_url')->url()->maxLength(500),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('student.nim')->label('NIM')->searchable(),
            TextColumn::make('title')->searchable()->wrap(),
            TextColumn::make('supervisor1.nidn')->label('Pembimbing 1'),
            TextColumn::make('status')->badge()->sortable(),
            TextColumn::make('defense_date')->dateTime(),
            TextColumn::make('created_at')->dateTime()->sortable(),
        ])->actions([
            EditAction::make()->visible(fn(Thesis $record): bool => !in_array($record->status, ['completed', 'cancelled'], true)),
            DeleteAction::make()->visible(fn(Thesis $record): bool => $record->status === 'proposed'),
        ])->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [SupervisionsRelationManager::class];
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ListThesiss::route('/'), 'create' => Pages\CreateThesis::route('/create'), 'edit' => Pages\EditThesis::route('/{record}/edit')];
    }
}
