<?php

namespace App\Filament\Resources\CurriculumTemplates;

use App\Filament\Clusters\CurriculumCluster;
use App\Filament\Resources\CurriculumTemplates\Pages\CreateCurriculumTemplate;
use App\Filament\Resources\CurriculumTemplates\Pages\EditCurriculumTemplate;
use App\Filament\Resources\CurriculumTemplates\Pages\ListCurriculumTemplates;
use App\Filament\Resources\CurriculumTemplates\Pages\ViewCurriculumTemplate;
use App\Filament\Resources\CurriculumTemplates\Schemas\CurriculumTemplateForm;
use App\Filament\Resources\CurriculumTemplates\Schemas\CurriculumTemplateInfolist;
use App\Filament\Resources\CurriculumTemplates\Tables\CurriculumTemplatesTable;
use App\Models\CurriculumTemplate;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use BackedEnum;
use UnitEnum;

class CurriculumTemplateResource extends Resource
{
    protected static ?string $cluster = CurriculumCluster::class;
    protected static ?string $slug = 'curriculum-templates';
    protected static ?string $model = CurriculumTemplate::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentDuplicate;
    // protected static string|UnitEnum|null $navigationGroup = 'Kurikulum & Mata Kuliah';
    protected static ?string $navigationLabel = 'Template Kurikulum';
    protected static ?string $modelLabel = 'Template Kurikulum';
    protected static ?string $pluralModelLabel = 'Template Kurikulum';
    protected static ?string $recordTitleAttribute = 'Template Kurikulum';
    protected static ?int $navigationSort = 2;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['curriculum'])
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function form(Schema $schema): Schema
    {
        return CurriculumTemplateForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CurriculumTemplateInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CurriculumTemplatesTable::configure($table);
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
            'index' => ListCurriculumTemplates::route('/'),
            'create' => CreateCurriculumTemplate::route('/create'),
            'view' => ViewCurriculumTemplate::route('/{record}'),
            'edit' => EditCurriculumTemplate::route('/{record}/edit'),
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
