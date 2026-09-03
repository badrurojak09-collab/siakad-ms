<?php
namespace App\Filament\Resources\ThesisGrades;

use App\Models\ThesisGrade;
use Filament\Forms\Components\{TextInput, Textarea};
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\{Actions\DeleteAction, Actions\EditAction};
use BackedEnum;
use UnitEnum;

class ThesisGradeResource extends Resource
{
    protected static ?string $model = ThesisGrade::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static string|UnitEnum|null $navigationGroup = 'Tugas Akhir';
    protected static ?string $navigationLabel = 'Tugas Akhir';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('component')->label('Komponen')->maxLength(255),
            TextInput::make('letter_grade')->label('Nilai Huruf')->maxLength(255),
            Textarea::make('description')->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([TextColumn::make('id')->sortable(), TextColumn::make('code')->searchable(), TextColumn::make('name')->searchable(), TextColumn::make('status')->badge(), TextColumn::make('created_at')->dateTime()->sortable()])->actions([EditAction::make(), DeleteAction::make()])->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ListThesisGrades::route('/'), 'create' => Pages\CreateThesisGrade::route('/create'), 'edit' => Pages\EditThesisGrade::route('/{record}/edit')];
    }
}
