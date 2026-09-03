<?php

namespace App\Filament\Resources\CurriculumTemplates\Schemas;

use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class CurriculumTemplateForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Template Kurikulum')
                    ->description('Data Template Kurikulum')
                    ->schema([
                        Select::make('curriculum_id')
                            ->label('Kurikulum Acuan')
                            ->relationship(
                                name: 'curriculum',
                                titleAttribute: 'name',
                                modifyQueryUsing: fn(Builder $query) => $query->when(
                                    filament()->getTenant(),
                                    fn($q, $tenant) => $q->where('tenant_id', $tenant->getKey())
                                )
                            )
                            ->searchable()
                            ->preload()
                            ->required(),
                        TextInput::make('entry_year')
                            ->label('Tahun Angkatan/Masuk')
                            ->numeric()
                            ->length(4)
                            ->default((int) date('Y'))
                            ->required(),
                        TextInput::make('total_credits_required')
                            ->label('Total SKS Lulus')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(200)
                            ->default(144)
                            ->required(),
                        TextInput::make('min_sks_per_semester')
                            ->label('Min SKS / Semester')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(24)
                            ->default(12)
                            ->required(),
                        TextInput::make('max_sks_per_semester')
                            ->label('Max SKS / Semester')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(24)
                            ->default(24)
                            ->required(),
                        KeyValue::make('metadata')
                            ->label('Metadata Tambahan')
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull()
                    ->columns(2),
            ]);
    }
}
