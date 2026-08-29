<?php

namespace App\Filament\Resources\AcademicTranscripts\RelationManagers;

use Filament\Actions\{CreateAction, DeleteAction, EditAction};
use Filament\Forms\Components\{Select, TextInput};
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';
    protected static ?string $title = 'Rincian Nilai';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('course_id')->relationship('course', 'name')->searchable()->preload()->required(),
            Select::make('student_grade_id')->relationship('grade', 'id')->searchable()->preload()->nullable(),
            TextInput::make('credits')->numeric()->minValue(0)->required(), TextInput::make('score')->numeric()->minValue(0)->maxValue(100),
            TextInput::make('letter_grade')->required()->maxLength(3), TextInput::make('grade_point')->numeric()->minValue(0)->maxValue(4), TextInput::make('quality_points')->numeric()->minValue(0),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('course.code')->label('Kode')->searchable(), TextColumn::make('course.name')->label('Mata Kuliah')->searchable(), TextColumn::make('credits')->label('SKS'),
            TextColumn::make('score'), TextColumn::make('letter_grade')->label('Huruf'), TextColumn::make('grade_point')->label('Bobot'), TextColumn::make('quality_points')->label('Mutu'),
        ])->headerActions([CreateAction::make()->visible(fn (): bool => $this->getOwnerRecord()->status !== 'final')])->actions([
            EditAction::make()->visible(fn (): bool => $this->getOwnerRecord()->status !== 'final'),
            DeleteAction::make()->visible(fn (): bool => $this->getOwnerRecord()->status !== 'final'),
        ]);
    }
}
