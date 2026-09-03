<?php

namespace App\Filament\Resources\Semesters\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class SemesterForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('tenant_id')
                    ->relationship('tenant', 'name'),
                Select::make('academic_year_id')
                    ->relationship('academic', 'year_code')
                    ->label('Tahun Ajaran'),
                TextInput::make('semester_type')
                    ->required(),
                DatePicker::make('start_date')
                    ->required(),
                DatePicker::make('end_date')
                    ->required(),
                DatePicker::make('krs_start_date'),
                DatePicker::make('krs_end_date'),
                DatePicker::make('exam_start_date'),
                DatePicker::make('exam_end_date'),
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
