<?php

namespace App\Filament\Resources\CourseEquivalencies\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class CourseEquivalencyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Ekuivalensi Matakuliah')
                    ->description('Data Ekuivalensi Matakuliah')
                    ->schema([
                        Select::make('student_id')
                            ->label('Mahasiswa')
                            ->relationship(
                                name: 'student',
                                // Menggunakan relasi nested 'user.name'
                                titleAttribute: 'id',
                                modifyQueryUsing: fn(Builder $query) => $query
                                    ->with('user')
                                    ->when(
                                        filament()->getTenant(),
                                        fn($q, $tenant) => $q->where('tenant_id', $tenant->getKey())
                                    )
                            )
                            ->getOptionLabelFromRecordUsing(fn($record) => "[{$record->nim}] " . ($record->user?->name ?? 'N/A'))
                            ->searchable(['nim'])
                            ->preload()
                            ->required(),
                        Select::make('original_course_id')
                            ->label('Mata Kuliah Asal (Lama)')
                            ->relationship(
                                name: 'originalCourse',
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
                            ->live(),
                        Select::make('equivalent_course_id')
                            ->label('Mata Kuliah Diakui (Baru)')
                            ->relationship(
                                name: 'equivalentCourse',
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
                            ->different('original_course_id', 'Mata kuliah penyetaraan tidak boleh sama dengan mata kuliah asal.'),
                        Textarea::make('reason')
                            ->label('Alasan / Catatan Penyetaraan')
                            ->placeholder('Contoh: Penyetaraan Kurikulum 2020 ke Kurikulum 2024')
                            ->columnSpanFull(),
                        Select::make('approved_by')
                            ->label('Disetujui Oleh')
                            ->relationship(
                                name: 'approver',
                                titleAttribute: 'name',
                                modifyQueryUsing: fn(Builder $query) => $query->when(
                                    filament()->getTenant(),
                                    fn($q, $tenant) => $q->where('tenant_id', $tenant->getKey())
                                )
                            )
                            ->searchable()
                            ->preload()
                            ->nullable(),
                        DateTimePicker::make('approved_at')
                            ->label('Tanggal Disetujui')
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
