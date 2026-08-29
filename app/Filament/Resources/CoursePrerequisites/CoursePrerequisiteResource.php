<?php
namespace App\Filament\Resources\CoursePrerequisites;

use App\Models\CoursePrerequisite;
use App\Filament\Resources\CoursePrerequisites\Pages;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\{Actions\DeleteAction, Actions\EditAction, Columns\IconColumn, Columns\TextColumn, Table};
use BackedEnum;
use UnitEnum;

class CoursePrerequisiteResource extends Resource
{
    protected static ?string $model = CoursePrerequisite::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedLink;
    protected static string|UnitEnum|null $navigationGroup = 'Kurikulum & Mata Kuliah';
    protected static ?string $navigationLabel = 'Prasyarat Mata Kuliah';
    protected static ?string $modelLabel = 'Prasyarat Mata Kuliah';
    protected static ?string $pluralModelLabel = 'Prasyarat Mata Kuliah';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('course_id')->label('Mata Kuliah')->relationship('course', 'name')->searchable()->preload()->required(),
            Select::make('prerequisite_course_id')->label('Mata Kuliah Prasyarat')->relationship('prerequisiteCourse', 'name')->searchable()->preload()->required()->different('course_id'),
            Select::make('is_mandatory')->label('Sifat Prasyarat')->options([1 => 'Wajib', 0 => 'Opsional'])->default(1)->required(),
        ]);
    }
    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('course.code')->label('Kode Mata Kuliah')->searchable(), TextColumn::make('course.name')->label('Mata Kuliah')->searchable(), TextColumn::make('prerequisiteCourse.code')->label('Kode Prasyarat')->searchable(), TextColumn::make('prerequisiteCourse.name')->label('Mata Kuliah Prasyarat')->searchable(), IconColumn::make('is_mandatory')->label('Wajib')->boolean(),
        ])->actions([EditAction::make()->label('Ubah'), DeleteAction::make()->label('Hapus')->requiresConfirmation()])->defaultSort('course_id');
    }
    public static function getPages(): array { return ['index' => Pages\ListCoursePrerequisites::route('/'), 'create' => Pages\CreateCoursePrerequisite::route('/create'), 'edit' => Pages\EditCoursePrerequisite::route('/{record}/edit')]; }
}
