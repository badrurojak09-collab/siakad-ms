<?php

namespace App\Filament\Resources\CourseClasss;

use App\Models\CourseClass;
use App\Filament\Resources\CourseClasss\Pages;
use Filament\Forms\Components\{Select, TextInput};
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\{Columns\TextColumn, Table};
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use BackedEnum;
use UnitEnum;

class CourseClassResource extends Resource
{
    protected static ?string $slug = 'course-classes';
    protected static ?string $model = CourseClass::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static string|UnitEnum|null $navigationGroup = 'Perkuliahan';
    protected static ?string $navigationLabel = 'Kelas Perkuliahan';
    protected static ?string $modelLabel = 'Kelas Perkuliahan';
    protected static ?string $pluralModelLabel = 'Kelas Perkuliahan';
    public static function form(Schema $schema): Schema
    {
        return $schema->components([Select::make('course_id')->label('Mata Kuliah')->relationship('course', 'name')->searchable()->preload()->required(), Select::make('semester_id')->label('Semester')->relationship('semester', 'id')->searchable()->preload()->required(), TextInput::make('class_code')->label('Kode Kelas')->required()->maxLength(50), Select::make('lecturer_id')->label('Dosen Utama')->relationship('lecturer', 'nidn')->searchable()->preload()->nullable(), Select::make('co_lecturer_id')->label('Dosen Pendamping')->relationship('coLecturer', 'nidn')->searchable()->preload()->nullable(), TextInput::make('capacity')->label('Kapasitas')->numeric()->minValue(1)->required()->default(40), Select::make('status')->label('Status')->options(['planned' => 'Direncanakan', 'active' => 'Aktif', 'closed' => 'Ditutup'])->default('planned')->required()]);
    }
    public static function table(Table $table): Table
    {
        return $table->columns([TextColumn::make('class_code')->label('Kelas')->searchable(), TextColumn::make('course.code')->label('Kode Mata Kuliah'), TextColumn::make('course.name')->label('Mata Kuliah'), TextColumn::make('semester.id')->label('Semester'), TextColumn::make('capacity')->label('Kapasitas'), TextColumn::make('status')->label('Status')->badge()])->actions([EditAction::make()->label('Ubah')->visible(fn(CourseClass $r) => $r->status !== 'closed'), DeleteAction::make()->label('Hapus')->visible(fn(CourseClass $r) => $r->status === 'planned')->requiresConfirmation()]);
    }
    public static function getRelations(): array
    {
        return [\App\Filament\Resources\CourseClasss\RelationManagers\SchedulesRelationManager::class, \App\Filament\Resources\CourseClasss\RelationManagers\TeachingAssignmentsRelationManager::class];
    }
    public static function getPages(): array
    {
        return ['index' => Pages\ListCourseClasss::route('/'), 'create' => Pages\CreateCourseClass::route('/create'), 'edit' => Pages\EditCourseClass::route('/{record}/edit')];
    }
}
