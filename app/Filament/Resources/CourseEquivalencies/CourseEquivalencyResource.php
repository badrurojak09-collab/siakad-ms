<?php

namespace App\Filament\Resources\CourseEquivalencies;

use App\Filament\Clusters\CurriculumCluster;
use App\Filament\Resources\CourseEquivalencies\Pages\CreateCourseEquivalency;
use App\Filament\Resources\CourseEquivalencies\Pages\EditCourseEquivalency;
use App\Filament\Resources\CourseEquivalencies\Pages\ListCourseEquivalencies;
use App\Filament\Resources\CourseEquivalencies\Pages\ViewCourseEquivalency;
use App\Filament\Resources\CourseEquivalencies\Schemas\CourseEquivalencyForm;
use App\Filament\Resources\CourseEquivalencies\Schemas\CourseEquivalencyInfolist;
use App\Filament\Resources\CourseEquivalencies\Tables\CourseEquivalenciesTable;
use App\Models\CourseEquivalency;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use BackedEnum;
use UnitEnum;

class CourseEquivalencyResource extends Resource
{
    protected static ?string $cluster = CurriculumCluster::class;
    protected static ?string $slug = 'course-equivalencies';
    protected static ?string $model = CourseEquivalency::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArrowsRightLeft;
    // protected static string|UnitEnum|null $navigationGroup = 'Kurikulum & Mata Kuliah';
    protected static ?string $navigationLabel = 'Ekuivalensi Mata Kuliah';
    protected static ?string $modelLabel = 'Ekuivalensi Mata Kuliah';
    protected static ?string $pluralModelLabel = 'Ekuivalensi Mata Kuliah';
    protected static ?string $recordTitleAttribute = 'Ekuivalensi Matakuliah';
    protected static ?int $navigationSort = 6;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['student.user', 'originalCourse', 'equivalentCourse', 'approver'])
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function form(Schema $schema): Schema
    {
        return CourseEquivalencyForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CourseEquivalencyInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CourseEquivalenciesTable::configure($table);
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
            'index' => ListCourseEquivalencies::route('/'),
            'create' => CreateCourseEquivalency::route('/create'),
            'view' => ViewCourseEquivalency::route('/{record}'),
            'edit' => EditCourseEquivalency::route('/{record}/edit'),
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
