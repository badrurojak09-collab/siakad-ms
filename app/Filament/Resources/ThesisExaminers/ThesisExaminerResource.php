<?php

namespace App\Filament\Resources\ThesisExaminers;

use App\Filament\Resources\ThesisExaminers\Pages\CreateThesisExaminer;
use App\Filament\Resources\ThesisExaminers\Pages\EditThesisExaminer;
use App\Filament\Resources\ThesisExaminers\Pages\ListThesisExaminers;
use App\Filament\Resources\ThesisExaminers\Schemas\ThesisExaminerForm;
use App\Filament\Resources\ThesisExaminers\Tables\ThesisExaminersTable;
use App\Models\ThesisExaminer;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ThesisExaminerResource extends Resource
{
    protected static ?string $model = ThesisExaminer::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static string|UnitEnum|null $navigationGroup = 'Tugas Akhir';

    public static function form(Schema $schema): Schema
    {
        return ThesisExaminerForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ThesisExaminersTable::configure($table);
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
            'index' => ListThesisExaminers::route('/'),
            'create' => CreateThesisExaminer::route('/create'),
            'edit' => EditThesisExaminer::route('/{record}/edit'),
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
