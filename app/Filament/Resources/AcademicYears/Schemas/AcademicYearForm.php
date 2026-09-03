<?php

namespace App\Filament\Resources\AcademicYears\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class AcademicYearForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('tenant_id')
                    ->relationship('tenant', 'name'),
                TextInput::make('year_code')
                    ->required(),
                DatePicker::make('start_date')
                    ->required(),
                DatePicker::make('end_date')
                    ->required(),
                Toggle::make('is_active')
                    ->required(),
                KeyValue::make('metadata')
                    ->label('Metadata / Data Tambahan')
                    ->keyLabel('Nama Parameter')  // contoh: sk_number
                    ->valueLabel('Nilai')  // contoh: SK/2026/001
                    ->reorderable()
                    ->columnSpanFull(),
            ]);
    }
}
