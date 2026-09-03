<?php

namespace App\Filament\Resources\StudyPrograms\Schemas;

use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class StudyProgramForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informasi Program Studi')
                ->description('Data jenjang, akreditasi, dan pimpinan program studi.')
                ->schema([
                    Select::make('department_id')
                        ->label('Departemen / Jurusan')
                        ->relationship('department', 'name')
                        ->searchable()
                        ->preload()
                        ->nullable()
                        ->helperText('Pilih departemen tempat prodi ini bernaung (opsional).'),
                    TextInput::make('code')
                        ->label('Kode Prodi')
                        ->placeholder('Contoh: 55201')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('name')
                        ->label('Nama Program Studi')
                        ->placeholder('Contoh: Teknik Informatika')
                        ->required()
                        ->maxLength(255),
                    Select::make('level')
                        ->label('Jenjang Studi (Level)')
                        ->options([
                            'D3' => 'D3 (Ahli Madya)',
                            'D4' => 'D4 (Sarjana Terapan)',
                            'S1' => 'S1 (Sarjana)',
                            'S2' => 'S2 (Magister)',
                            'S3' => 'S3 (Doktor)',
                        ])
                        ->searchable()
                        ->nullable(),
                    Select::make('accreditation')
                        ->label('Akreditasi')
                        ->options([
                            'Unggul' => 'Unggul',
                            'Baik Sekali' => 'Baik Sekali',
                            'Baik' => 'Baik',
                            'A' => 'A',
                            'B' => 'B',
                            'C' => 'C',
                            'Tidak Terakreditasi' => 'Tidak Terakreditasi',
                        ])
                        ->searchable()
                        ->nullable(),
                    Select::make('head_of_program_id')
                        ->label('Kaprodi (Ketua Program Studi)')
                        ->relationship(
                            name: 'headOfProgram',
                            titleAttribute: 'id',
                            modifyQueryUsing: fn(Builder $query) => $query->with('user')  // 🚀 Eager loading cegah N+1
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
                ->columns(2)
                ->columnSpanFull(),
        ]);
    }
}
