<?php

namespace App\Filament\Resources\AcademicAdvisors;

use App\Models\AcademicAdvisor;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\{TextInput, Textarea};
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use BackedEnum;
use UnitEnum;

class AcademicAdvisorResource extends Resource
{
    protected static ?string $model = AcademicAdvisor::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static string|UnitEnum|null $navigationGroup = 'Perkuliahan';
    protected static ?string $navigationLabel = 'Mahasiswa & Dosen';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Textarea::make('description')->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([TextColumn::make('id')->sortable(), TextColumn::make('code')->searchable(), TextColumn::make('name')->searchable(), TextColumn::make('status')->badge(), TextColumn::make('created_at')->dateTime()->sortable()])->actions([EditAction::make(), DeleteAction::make()])->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ListAcademicAdvisors::route('/'), 'create' => Pages\CreateAcademicAdvisor::route('/create'), 'edit' => Pages\EditAcademicAdvisor::route('/{record}/edit')];
    }
}
