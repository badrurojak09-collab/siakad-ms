<?php

namespace App\Filament\Resources\CurriculumCourses\Schemas;

use App\Models\CurriculumCourse;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class CurriculumCourseForm
{
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Menyimpan ID tenant aktif ke dalam record secara otomatis
        $data['tenant_id'] = filament()->getTenant()?->getKey();

        return $data;
    }

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Mata Kuliah dalam Kurikulum')
                    ->description('Data perencanaan mata kuliah dalam kurikulum akademik per program studi.')
                    ->schema([
                        Select::make('curriculum_id')
                            ->label('Kurikulum')
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
                        Select::make('course_id')
                            ->label('Mata Kuliah')
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
                            ->required(),
                        TextInput::make('semester')
                            ->label('Semester')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(14)
                            ->default(1)
                            ->required(),
                        Toggle::make('is_mandatory')
                            ->label('Mata Kuliah Wajib')
                            ->default(true)
                            ->inline(false),
                        TextInput::make('concentration')
                            ->label('Konsentrasi / Minat')
                            ->placeholder('Contoh: Rekayasa Perangkat Lunak')
                            ->maxLength(255)
                            ->nullable(),
                        KeyValue::make('metadata')
                            ->label('Metadata Tambahan')
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull()
                    ->columns(2),
            ]);
    }
}
