<?php

namespace App\Filament\Resources\CoursePrerequisites;

use App\Filament\Resources\CoursePrerequisites\Pages\CreateCoursePrerequisite;
use App\Filament\Resources\CoursePrerequisites\Pages\EditCoursePrerequisite;
use App\Filament\Resources\CoursePrerequisites\Pages\ListCoursePrerequisites;
use App\Filament\Resources\CoursePrerequisites\Pages\ViewCoursePrerequisite;
use App\Filament\Resources\CoursePrerequisites\Schemas\CoursePrerequisiteForm;
use App\Filament\Resources\CoursePrerequisites\Schemas\CoursePrerequisiteInfolist;
use App\Filament\Resources\CoursePrerequisites\Tables\CoursePrerequisitesTable;
use App\Models\CoursePrerequisite;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use BackedEnum;
use UnitEnum;

class CoursePrerequisiteResource extends Resource
{
    protected static ?string $model = CoursePrerequisite::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|UnitEnum|null $navigationGroup = 'Kurikulum & Mata Kuliah';

    protected static ?string $navigationLabel = 'Prasyarat Mata Kuliah';

    protected static ?string $modelLabel = 'Prasyarat Mata Kuliah';

    protected static ?string $pluralModelLabel = 'Prasyarat Mata Kuliah';

    protected static ?string $recordTitleAttribute = 'Prasyarat Matakuliah';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['course', 'prerequisiteCourse'])
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function form(Schema $schema): Schema
    {
        return CoursePrerequisiteForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CoursePrerequisiteInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CoursePrerequisitesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCoursePrerequisites::route('/'),
            'create' => CreateCoursePrerequisite::route('/create'),
            'view' => ViewCoursePrerequisite::route('/{record}'),
            'edit' => EditCoursePrerequisite::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
