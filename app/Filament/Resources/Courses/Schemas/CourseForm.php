<?php declare(strict_types=1);

namespace App\Filament\Resources\Courses\Schemas;

use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CourseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Mata Kuliah')
                    ->description('Data identitas dan bobot SKS mata kuliah.')
                    ->schema([
                        TextInput::make('code')
                            ->label('Kode Mata Kuliah')
                            ->placeholder('Contoh: TIF101')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('name')
                            ->label('Nama Mata Kuliah')
                            ->placeholder('Contoh: Pemrograman Web Lanjut')
                            ->required()
                            ->maxLength(255),
                        Select::make('course_type')
                            ->label('Tipe Mata Kuliah')
                            ->options([
                                'Teori' => 'Teori',
                                'Praktikum' => 'Praktikum',
                                'Teori & Praktikum' => 'Teori & Praktikum',
                                'Lapangan' => 'Kuliah Kerja Lapangan / Magang',
                                'Skripsi' => 'Skripsi / Tugas Akhir',
                            ])
                            ->searchable()
                            ->nullable(),
                        // Grid 3 Kolom Khusus Pembagian SKS
                        Grid::make(3)
                            ->schema([
                                TextInput::make('credits')
                                    ->label('Total SKS')
                                    ->numeric()
                                    ->default(0)
                                    ->required()
                                    ->readOnly()  // Otomatis terhitung dari Teori + Praktik
                                    ->helperText('Otomatis dihitung dari Teori + Praktik.'),
                                TextInput::make('theory_credits')
                                    ->label('SKS Teori')
                                    ->numeric()
                                    ->default(0)
                                    ->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function ($get, $set) {
                                        $theory = (int) $get('theory_credits');
                                        $practice = (int) $get('practice_credits');
                                        $set('credits', $theory + $practice);
                                    }),
                                TextInput::make('practice_credits')
                                    ->label('SKS Praktik')
                                    ->numeric()
                                    ->default(0)
                                    ->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function ($get, $set) {
                                        $theory = (int) $get('theory_credits');
                                        $practice = (int) $get('practice_credits');
                                        $set('credits', $theory + $practice);
                                    }),
                            ]),
                        Textarea::make('description')
                            ->label('Deskripsi / Silabus Ringkas')
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
