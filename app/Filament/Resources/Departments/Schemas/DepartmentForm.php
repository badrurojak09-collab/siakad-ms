<?php

namespace App\Filament\Resources\Departments\Schemas;

use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class DepartmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informasi Departemen')
                ->description('Data dasar departemen / jurusan pada institusi.')
                ->schema([
                    Select::make('faculty_id')
                        ->label('Fakultas')
                        ->relationship('faculty', 'name')
                        ->searchable()
                        ->preload()
                        ->nullable()
                        ->helperText('Kosongkan jika institusi berbentuk Sekolah Tinggi / Akademi.'),
                    TextInput::make('code')
                        ->label('Kode Departemen')
                        ->placeholder('Contoh: DEP-TI')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('name')
                        ->label('Nama Departemen')
                        ->placeholder('Contoh: Departemen Teknik Informatika')
                        ->required()
                        ->maxLength(255),
                    Select::make('head_of_dept_id')
                        ->label('Ketua Departemen')
                        ->relationship(
                            name: 'headOfDepartment',
                            titleAttribute: 'id',
                            modifyQueryUsing: fn(Builder $query) => $query->with('user')
                        )
                        ->getOptionLabelFromRecordUsing(fn($record) => $record->user?->name ?? 'Tanpa Nama')
                        ->searchable(['users.name'])
                        ->preload()
                        ->nullable(),
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
