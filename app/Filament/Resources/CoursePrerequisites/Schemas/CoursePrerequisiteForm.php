<?php

namespace App\Filament\Resources\CoursePrerequisites\Schemas;

use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class CoursePrerequisiteForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Prasyarat Mata Kuliah')
                    ->description('Data perencanaan prasyarat mata kuliah')
                    ->schema([
                        Select::make('course_id')
                            ->label('Mata Kuliah Utama')
                            ->relationship(
                                name: 'course',
                                titleAttribute: 'name',
                                modifyQueryUsing: fn(Builder $query) => $query->when(
                                    filament()->getTenant(),
                                    fn($q, $tenant) => $q->where('tenant_id', $tenant->getKey())
                                )
                            )
                            ->getOptionLabelFromRecordUsing(fn($record) => "[{$record->code}] {$record->name}")
                            ->searchable()
                            ->preload()
                            ->required()
                            ->live(),  // Live untuk re-evaluate validasi prasyarat
                        Select::make('prerequisite_course_id')
                            ->label('Mata Kuliah Prasyarat (Syarat)')
                            ->relationship(
                                name: 'prerequisiteCourse',
                                titleAttribute: 'name',
                                modifyQueryUsing: fn(Builder $query) => $query->when(
                                    filament()->getTenant(),
                                    fn($q, $tenant) => $q->where('tenant_id', $tenant->getKey())
                                )
                            )
                            ->getOptionLabelFromRecordUsing(fn($record) => "[{$record->code}] {$record->name}")
                            ->searchable()
                            ->preload()
                            ->required()
                            // 🔒 Validasi: Matkul Syarat tidak boleh sama dengan Matkul Utama
                            ->different('course_id', 'Mata kuliah prasyarat tidak boleh sama dengan mata kuliah utama.'),
                        Toggle::make('is_mandatory')
                            ->label('Prasyarat Wajib (Harus Lulus)')
                            ->helperText('Jika nonaktif, matkul syarat cukup pernah diambil/ditempuh saja.')
                            ->default(true)
                            ->inline(false),
                        KeyValue::make('metadata')
                            ->label('Metadata Tambahan')
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull()
                    ->columns(2),
            ]);
    }
}
