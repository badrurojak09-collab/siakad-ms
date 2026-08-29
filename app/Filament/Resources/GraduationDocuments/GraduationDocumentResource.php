<?php

namespace App\Filament\Resources\GraduationDocuments;

use App\Filament\Resources\GraduationDocuments\Pages\CreateGraduationDocument;
use App\Filament\Resources\GraduationDocuments\Pages\EditGraduationDocument;
use App\Filament\Resources\GraduationDocuments\Pages\ListGraduationDocuments;
use App\Filament\Resources\GraduationDocuments\Schemas\GraduationDocumentForm;
use App\Filament\Resources\GraduationDocuments\Tables\GraduationDocumentsTable;
use App\Models\GraduationDocument;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GraduationDocumentResource extends Resource
{
    protected static ?string $model = GraduationDocument::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static string|UnitEnum|null $navigationGroup = 'Kelulusan';

    public static function form(Schema $schema): Schema
    {
        return GraduationDocumentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GraduationDocumentsTable::configure($table);
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
            'index' => ListGraduationDocuments::route('/'),
            'create' => CreateGraduationDocument::route('/create'),
            'edit' => EditGraduationDocument::route('/{record}/edit'),
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
