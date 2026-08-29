<?php

namespace App\Filament\Resources\ThesisRevisions\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ThesisRevisionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('tenant_id')
                    ->relationship('tenant', 'name'),
                TextInput::make('thesis_id')
                    ->required()
                    ->numeric(),
                TextInput::make('revision_no')
                    ->required()
                    ->numeric(),
                Textarea::make('description')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('status')
                    ->required()
                    ->default('submitted'),
                DateTimePicker::make('submitted_at'),
                DateTimePicker::make('approved_at'),
                TextInput::make('approved_by')
                    ->numeric(),
            ]);
    }
}
