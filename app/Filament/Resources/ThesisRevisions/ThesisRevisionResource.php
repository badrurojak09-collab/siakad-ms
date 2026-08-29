<?php

namespace App\Filament\Resources\ThesisRevisions;

use App\Filament\Resources\ThesisRevisions\Pages\CreateThesisRevision;
use App\Filament\Resources\ThesisRevisions\Pages\EditThesisRevision;
use App\Filament\Resources\ThesisRevisions\Pages\ListThesisRevisions;
use App\Filament\Resources\ThesisRevisions\Schemas\ThesisRevisionForm;
use App\Filament\Resources\ThesisRevisions\Tables\ThesisRevisionsTable;
use App\Models\ThesisRevision;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ThesisRevisionResource extends Resource
{
    protected static ?string $model = ThesisRevision::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static string|UnitEnum|null $navigationGroup = 'Tugas Akhir';

    public static function form(Schema $schema): Schema
    {
        return ThesisRevisionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ThesisRevisionsTable::configure($table);
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
            'index' => ListThesisRevisions::route('/'),
            'create' => CreateThesisRevision::route('/create'),
            'edit' => EditThesisRevision::route('/{record}/edit'),
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
