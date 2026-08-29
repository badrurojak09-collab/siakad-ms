<?php

namespace App\Filament\Resources\StudyPrograms;

use App\Models\StudyProgram;
use App\Filament\Resources\StudyPrograms\Pages;
use Filament\Forms\Components\{Select, TextInput};
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\{Columns\TextColumn, Table};
use Filament\Actions\{EditAction, DeleteAction};
use BackedEnum;
use UnitEnum;

class StudyProgramResource extends Resource
{
    protected static ?string $slug = 'study-programs';
    protected static ?string $model = StudyProgram::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAcademicCap;
    protected static string|UnitEnum|null $navigationGroup = 'Organisasi Akademik';
    protected static ?string $navigationLabel = 'Program Studi';
    protected static ?string $modelLabel = 'Program Studi';
    protected static ?string $pluralModelLabel = 'Program Studi';
    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('department_id')
                ->label('Departemen')
                ->relationship('department', 'name')
                ->searchable()
                ->preload()
                ->required(),
            TextInput::make('code')
                ->label('Kode Program Studi')
                ->required()
                ->unique(ignoreRecord: true)
                ->maxLength(30),
            TextInput::make('name')
                ->label('Nama Program Studi')
                ->required()->maxLength(255),
            Select::make('level')
                ->label('Jenjang')
                ->options([
                    'D3' => 'Diploma 3',
                    'D4' => 'Diploma 4',
                    '
                S1' => 'Sarjana',
                    'S2' => 'Magister',
                    'S3' => 'Doktor'
                ])
                ->nullable(),
            Select::make('accreditation')
                ->label('Akreditasi')
                ->options([
                    'Unggul' => 'Unggul',
                    'Baik Sekali' => 'Baik Sekali',
                    'Baik' => 'Baik',
                    'Tidak Terakreditasi' => 'Tidak Terakreditasi'
                ])
                ->nullable(),
            Select::make('head_of_program_id')
                ->label('Ketua Program Studi')
                ->relationship('headOfProgram', 'nidn')
                ->searchable()
                ->preload()
                ->nullable()
        ]);
    }
    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('department.faculty.name')
                ->label('Fakultas')
                ->searchable(),
            TextColumn::make('department.name')
                ->label('Departemen')
                ->searchable(),
            TextColumn::make('code')
                ->label('Kode')
                ->searchable()
                ->sortable(),
            TextColumn::make('name')
                ->label('Nama Program Studi')
                ->searchable()
                ->sortable(),
            TextColumn::make('level')
                ->label('Jenjang')
                ->badge(),
            TextColumn::make('accreditation')
                ->label('Akreditasi')
                ->badge(),
            TextColumn::make('headOfProgram.user.name')
                ->label('Ketua Program Studi')
                ->placeholder('Belum ditetapkan')
        ])
            ->actions([
                EditAction::make()
                    ->label('Ubah'),
                DeleteAction::make()
                    ->label('Hapus')
                    ->requiresConfirmation()
            ])
            ->defaultSort('code');
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStudyPrograms::route('/'),
            'create' => Pages\CreateStudyProgram::route('/create'),
            'edit' => Pages\EditStudyProgram::route('/{record}/edit')
        ];
    }
}
