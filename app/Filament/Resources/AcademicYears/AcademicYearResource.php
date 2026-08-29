<?php

namespace App\Filament\Resources\AcademicYears;

use App\Models\AcademicYear;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Forms\Components\{TextInput, Textarea};
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Support\Icons\Heroicon;
use Filament\Actions\{EditAction, DeleteAction};
use BackedEnum;
use UnitEnum;

class AcademicYearResource extends Resource
{
    protected static ?string $model = AcademicYear::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static string|UnitEnum|null $navigationGroup = 'Data Akademik';
    protected static ?string $navigationLabel = 'Periode Akademik';
    public static function form(Schema $schema): Schema
    {
        return $schema->components([TextInput::make('year_code')->label('Year Code')->maxLength(255), Textarea::make('description')->columnSpanFull(),]);
    }
    public static function table(Table $table): Table
    {
        return $table->columns([TextColumn::make('id')->sortable(), TextColumn::make('code')->searchable(), TextColumn::make('name')->searchable(), TextColumn::make('status')->badge(), TextColumn::make('created_at')->dateTime()->sortable()])->actions([EditAction::make(), DeleteAction::make()])->defaultSort('created_at', 'desc');
    }
    public static function getPages(): array
    {
        return ['index' => Pages\ListAcademicYears::route('/'), 'create' => Pages\CreateAcademicYear::route('/create'), 'edit' => Pages\EditAcademicYear::route('/{record}/edit')];
    }
}
