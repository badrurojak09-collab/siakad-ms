<?php

namespace App\Filament\Resources\KrsHeaders\RelationManagers;

use Filament\Actions\{CreateAction, DeleteAction, EditAction};
use Filament\Forms\Components\{Select, TextInput};
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DetailsRelationManager extends RelationManager
{
    protected static string $relationship = 'details';
    protected static ?string $title = 'Mata Kuliah KRS';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('course_class_id')->label('Kelas Perkuliahan')->relationship('courseClass', 'class_code')->searchable()->preload()->required(),
            Select::make('status')->label('Status')->options(['registered' => 'Terdaftar', 'dropped' => 'Dibatalkan'])->default('registered')->required(),
            TextInput::make('registered_at')->label('Terdaftar Pada')->disabled()->dehydrated(false),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('courseClass.course.code')->label('Kode')->searchable(),
            TextColumn::make('courseClass.course.name')->label('Mata Kuliah')->searchable(),
            TextColumn::make('courseClass.class_code')->label('Kelas'),
            TextColumn::make('status')->label('Status')->badge(),
            TextColumn::make('registered_at')->label('Terdaftar Pada')->dateTime('d M Y H:i'),
        ])->headerActions([CreateAction::make()->label('Tambah Mata Kuliah')])->actions([
            EditAction::make()->label('Ubah'), DeleteAction::make()->label('Hapus')->requiresConfirmation(),
        ]);
    }
}
