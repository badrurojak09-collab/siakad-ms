<?php

namespace App\Filament\Resources\Applicants\RelationManagers;

use Filament\Actions\{CreateAction, DeleteAction, EditAction};
use Filament\Forms\Components\{Select, TextInput};
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SelectionsRelationManager extends RelationManager
{
    protected static string $relationship = 'selections';
    protected static ?string $title = 'Pilihan Program Studi';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('study_program_id')->relationship('studyProgram', 'name')->searchable()->preload()->required(),
            TextInput::make('choice_order')->numeric()->minValue(1)->required(), TextInput::make('score')->numeric()->minValue(0),
            Select::make('status')->options(['registered' => 'Terdaftar', 'selected' => 'Terpilih', 'rejected' => 'Ditolak'])->default('registered')->required(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('choice_order')->label('Pilihan')->sortable(), TextColumn::make('studyProgram.name')->label('Program Studi')->searchable(), TextColumn::make('score')->sortable(), TextColumn::make('status')->badge(),
        ])->headerActions([CreateAction::make()])->actions([EditAction::make(), DeleteAction::make()]);
    }
}
