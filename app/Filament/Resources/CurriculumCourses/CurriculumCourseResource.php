<?php

namespace App\Filament\Resources\CurriculumCourses;

use App\Filament\Clusters\CurriculumCluster;
use App\Filament\Resources\CurriculumCourses\Pages\CreateCurriculumCourse;
use App\Filament\Resources\CurriculumCourses\Pages\EditCurriculumCourse;
use App\Filament\Resources\CurriculumCourses\Pages\ListCurriculumCourses;
use App\Filament\Resources\CurriculumCourses\Pages\ViewCurriculumCourse;
use App\Filament\Resources\CurriculumCourses\Schemas\CurriculumCourseForm;
use App\Filament\Resources\CurriculumCourses\Schemas\CurriculumCourseInfolist;
use App\Filament\Resources\CurriculumCourses\Tables\CurriculumCoursesTable;
use App\Models\CurriculumCourse;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use BackedEnum;
use UnitEnum;

class CurriculumCourseResource extends Resource
{
    protected static ?string $cluster = CurriculumCluster::class;
    protected static ?string $slug = 'curriculum-courses';
    protected static ?string $model = CurriculumCourse::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedListBullet;
    // protected static string|UnitEnum|null $navigationGroup = 'Kurikulum & Mata Kuliah';
    protected static ?string $navigationLabel = 'Mata Kuliah dalam Kurikulum';
    protected static ?string $modelLabel = 'Mata Kuliah dalam Kurikulum';
    protected static ?string $pluralModelLabel = 'Mata Kuliah dalam Kurikulum';
    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return CurriculumCourseForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CurriculumCourseInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CurriculumCoursesTable::configure($table);
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
            'index' => ListCurriculumCourses::route('/'),
            'create' => CreateCurriculumCourse::route('/create'),
            'view' => ViewCurriculumCourse::route('/{record}'),
            'edit' => EditCurriculumCourse::route('/{record}/edit'),
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
