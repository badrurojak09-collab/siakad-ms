<?php

namespace App\Filament\Resources\Applicants\RelationManagers;

use Filament\Actions\{CreateAction, DeleteAction, EditAction};
use Filament\Forms\Components\{Select, TextInput};
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DocumentsRelationManager extends RelationManager
{
    protected static string $relationship = 'documents';
    protected static ?string $title = 'Dokumen Pendaftar';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('document_type')->required()->maxLength(80), TextInput::make('file_url')->url()->required()->maxLength(500),
            Select::make('status')->options(['pending' => 'Menunggu', 'verified' => 'Verified', 'rejected' => 'Ditolak'])->default('pending')->required(),
            TextInput::make('verification_note')->maxLength(500),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('document_type')->label('Jenis')->searchable(), TextColumn::make('status')->badge(), TextColumn::make('file_url')->url(fn ($record) => $record->file_url)->limit(50), TextColumn::make('verified_at')->dateTime(),
        ])->headerActions([CreateAction::make()])->actions([EditAction::make(), DeleteAction::make()]);
    }
}
