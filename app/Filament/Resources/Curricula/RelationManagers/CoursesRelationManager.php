<?php
namespace App\Filament\Resources\Curricula\RelationManagers;

use Filament\Forms\Components\{Select, TextInput};
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\{Columns\TextColumn, Table};
use Filament\{Actions\CreateAction, Actions\DeleteAction, Actions\EditAction};
use Illuminate\Database\Eloquent\Builder;

class CoursesRelationManager extends RelationManager
{
    protected static string $relationship = 'courses';
    protected static ?string $title = 'Mata Kuliah Kurikulum';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('course_id')
                ->label('Mata Kuliah')
                ->relationship(
                    name: 'course',
                    titleAttribute: 'name',
                    modifyQueryUsing: fn(Builder $query) => $query->with('studyProgram')  // Eager load
                )
                ->getOptionLabelFromRecordUsing(fn($record) => "{$record->code} - {$record->name}")
                ->searchable(['code', 'name'])
                ->preload()
                ->required(),
            TextInput::make('semester')
                ->label('Semester Paket')
                ->numeric()
                ->minValue(1)
                ->maxValue(14)
                ->placeholder('Contoh: 3')
                ->required(),
            Select::make('is_mandatory')
                ->label('Sifat Mata Kuliah')
                ->options([
                    1 => 'Wajib',
                    0 => 'Pilihan',
                ])
                ->default(1)
                ->required(),
            TextInput::make('concentration')
                ->label('Konsentrasi / Peminatan')
                ->placeholder('Contoh: Rekayasa Perangkat Lunak')
                ->nullable(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn(Builder $query) => $query->with('course'))  // 🚀 Eager loading cegah N+1
            ->columns([
                TextColumn::make('course.code')
                    ->label('Kode Matkul')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('course.name')
                    ->label('Nama Mata Kuliah')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('semester')
                    ->label('Semester')
                    ->sortable(),
                TextColumn::make('is_mandatory')
                    ->label('Sifat')
                    ->formatStateUsing(fn($state) => $state ? 'Wajib' : 'Pilihan')
                    ->badge()
                    ->color(fn($state) => $state ? 'success' : 'warning'),
                TextColumn::make('concentration')
                    ->label('Konsentrasi')
                    ->placeholder('-'),
            ])
            ->headerActions([CreateAction::make()->label('Tambah Mata Kuliah')])
            ->actions([
                EditAction::make()->label('Ubah'),
                DeleteAction::make()->label('Hapus')->requiresConfirmation()
            ]);
    }
}
