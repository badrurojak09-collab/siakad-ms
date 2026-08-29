<?php

namespace App\Filament\Resources\ThesisExaminers\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ThesisExaminerForm
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
                TextInput::make('lecturer_id')
                    ->required()
                    ->numeric(),
                TextInput::make('role')
                    ->required()
                    ->default('examiner'),
                TextInput::make('status')
                    ->required()
                    ->default('assigned'),
                DateTimePicker::make('assigned_at'),
            ]);
    }
}
