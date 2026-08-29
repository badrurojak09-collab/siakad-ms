<?php

namespace App\Filament\Resources\GraduationDocuments\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class GraduationDocumentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('tenant_id')
                    ->relationship('tenant', 'name'),
                TextInput::make('graduation_id')
                    ->required()
                    ->numeric(),
                TextInput::make('document_type')
                    ->required(),
                TextInput::make('file_url')
                    ->url(),
                TextInput::make('verification_hash'),
                TextInput::make('generated_by')
                    ->numeric(),
                DateTimePicker::make('generated_at'),
                Textarea::make('metadata')
                    ->columnSpanFull(),
            ]);
    }
}
