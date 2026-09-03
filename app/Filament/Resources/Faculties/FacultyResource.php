<?php

namespace App\Filament\Resources\Faculties;

use App\Filament\Resources\Faculties\Pages\CreateFaculty;
use App\Filament\Resources\Faculties\Pages\EditFaculty;
use App\Filament\Resources\Faculties\Pages\ListFaculties;
use App\Filament\Resources\Faculties\Pages\ViewFaculty;
use App\Filament\Resources\Faculties\Schemas\FacultyForm;
use App\Filament\Resources\Faculties\Schemas\FacultyInfolist;
use App\Filament\Resources\Faculties\Tables\FacultiesTable;
use App\Models\Faculty;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use BackedEnum;
use UnitEnum;

class FacultyResource extends Resource
{
    protected static ?string $slug = 'faculties';
    protected static ?string $model = Faculty::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice;
    protected static string|UnitEnum|null $navigationGroup = 'Organisasi Akademik';
    protected static ?string $navigationLabel = 'Fakultas';
    protected static ?string $modelLabel = 'Fakultas';
    protected static ?string $pluralModelLabel = 'Fakultas';
    protected static ?string $recordTitleAttribute = 'Fakultas';

    public static function form(Schema $schema): Schema
    {
        return FacultyForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return FacultyInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FacultiesTable::configure($table);
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
            'index' => ListFaculties::route('/'),
            'create' => CreateFaculty::route('/create'),
            'view' => ViewFaculty::route('/{record}'),
            'edit' => EditFaculty::route('/{record}/edit'),
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
