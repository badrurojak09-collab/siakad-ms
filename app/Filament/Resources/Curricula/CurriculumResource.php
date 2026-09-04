<?php

namespace App\Filament\Resources\Curricula;

use App\Filament\Clusters\CurriculumCluster;
use App\Filament\Resources\Curricula\Pages\CreateCurriculum;
use App\Filament\Resources\Curricula\Pages\EditCurriculum;
use App\Filament\Resources\Curricula\Pages\ListCurricula;
use App\Filament\Resources\Curricula\Pages\ViewCurriculum;
use App\Filament\Resources\Curricula\RelationManagers\CoursesRelationManager;
use App\Filament\Resources\Curricula\Schemas\CurriculumForm;
use App\Filament\Resources\Curricula\Schemas\CurriculumInfolist;
use App\Filament\Resources\Curricula\Tables\CurriculaTable;
use App\Models\Curriculum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use BackedEnum;
use UnitEnum;

class CurriculumResource extends Resource
{
    protected static ?string $slug = 'curriculums';
    protected static ?string $cluster = CurriculumCluster::class;
    protected static ?string $model = Curriculum::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBookOpen;
    // protected static string|UnitEnum|null $navigationGroup = 'Kurikulum & Mata Kuliah';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationLabel = 'Kurikulum';
    protected static ?string $modelLabel = 'Kurikulum';
    protected static ?string $pluralModelLabel = 'Kurikulum';
    protected static ?string $recordTitleAttribute = 'Kurikulum';

    public static function form(Schema $schema): Schema
    {
        return CurriculumForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CurriculumInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CurriculaTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            CoursesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCurricula::route('/'),
            'create' => CreateCurriculum::route('/create'),
            'view' => ViewCurriculum::route('/{record}'),
            'edit' => EditCurriculum::route('/{record}/edit'),
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
