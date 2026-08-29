<?php

namespace App\Filament\Resources\Courses;

use App\Models\Course;
use App\Filament\Resources\Courses\Pages;
use Filament\Forms\Components\{Select, TextInput, Textarea};
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\{Columns\TextColumn, Table};
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use BackedEnum;
use UnitEnum;

class CourseResource extends Resource
{
    protected static ?string $model = Course::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBookOpen;
    protected static string|UnitEnum|null $navigationGroup = 'Kurikulum & Mata Kuliah';
    protected static ?string $navigationLabel = 'Mata Kuliah';
    protected static ?string $modelLabel = 'Mata Kuliah';
    protected static ?string $pluralModelLabel = 'Mata Kuliah';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('code')->label('Kode Mata Kuliah')->required()->unique(ignoreRecord: true)->maxLength(30),
            TextInput::make('name')->label('Nama Mata Kuliah')->required()->maxLength(255),
            TextInput::make('credits')->label('Total SKS')->numeric()->minValue(0)->maxValue(20)->required(),
            TextInput::make('theory_credits')->label('SKS Teori')->numeric()->minValue(0)->maxValue(20)->default(0)->required(),
            TextInput::make('practice_credits')->label('SKS Praktik')->numeric()->minValue(0)->maxValue(20)->default(0)->required(),
            Select::make('course_type')->label('Jenis Mata Kuliah')->options(['mandatory' => 'Wajib', 'elective' => 'Pilihan', 'general' => 'Umum'])->nullable(),
            Textarea::make('description')->label('Deskripsi')->columnSpanFull(),
        ]);
    }
    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('code')->label('Kode')->searchable()->sortable(),
            TextColumn::make('name')->label('Nama Mata Kuliah')->searchable()->sortable(),
            TextColumn::make('credits')->label('SKS')->sortable(),
            TextColumn::make('theory_credits')->label('SKS Teori'),
            TextColumn::make('practice_credits')->label('SKS Praktik'),
            TextColumn::make('course_type')->label('Jenis')->badge(),
        ])->actions([EditAction::make()->label('Ubah'), DeleteAction::make()->label('Hapus')->requiresConfirmation()])->defaultSort('code');
    }
    public static function getPages(): array
    {
        return ['index' => Pages\ListCourses::route('/'), 'create' => Pages\CreateCourse::route('/create'), 'edit' => Pages\EditCourse::route('/{record}/edit')];
    }
}
