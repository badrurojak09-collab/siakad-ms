<?php

namespace App\Filament\Resources\Curricula\Schemas;

use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CurriculumForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informasi Kurikulum')
                ->description('Data perencanaan kurikulum akademik per program studi.')
                ->schema([
                    Select::make('study_program_id')
                        ->label('Program Studi')
                        ->relationship('studyProgram', 'name')
                        ->searchable()
                        ->preload()
                        ->required(),
                    TextInput::make('name')
                        ->label('Nama Kurikulum')
                        ->placeholder('Contoh: Kurikulum Merdeka 2024')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('year')
                        ->label('Tahun Mula')
                        ->numeric()
                        ->placeholder('Contoh: 2024')
                        ->required()
                        ->minValue(2000)
                        ->maxValue(2100),
                    Select::make('status')
                        ->label('Status Kurikulum')
                        ->options([
                            'draft' => 'Draft',
                            'active' => 'Aktif',
                            'archived' => 'Arsip / Tidak Aktif',
                        ])
                        ->default('draft')
                        ->required(),
                    Textarea::make('description')
                        ->label('Deskripsi / Keterangan')
                        ->rows(3)
                        ->columnSpanFull(),
                    KeyValue::make('metadata')
                        ->label('Metadata / Data Tambahan')
                        ->keyLabel('Parameter')
                        ->valueLabel('Nilai')
                        ->reorderable()
                        ->columnSpanFull(),
                ])
                ->columnSpanFull()
                ->columns(2),
        ]);
    }
}
