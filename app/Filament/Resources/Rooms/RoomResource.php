<?php
namespace App\Filament\Resources\Rooms;

use App\Filament\Resources\Rooms\Pages;
use App\Models\Room;
use Filament\Forms\Components\{Select, TextInput};
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\{Columns\TextColumn, Filters\SelectFilter, Table};
use Filament\{Actions\DeleteAction, Actions\EditAction};
use BackedEnum;
use UnitEnum;

class RoomResource extends Resource
{
    protected static ?string $slug = 'rooms';
    protected static ?string $model = Room::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice;
    protected static string|UnitEnum|null $navigationGroup = 'Penjadwalan & Ruang';
    protected static ?string $navigationLabel = 'Ruang';
    protected static ?string $modelLabel = 'Ruang';
    protected static ?string $pluralModelLabel = 'Ruang';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([TextInput::make('code')->label('Kode Ruang')->required()->unique(ignoreRecord: true)->maxLength(30), TextInput::make('name')->label('Nama Ruang')->required()->maxLength(100), TextInput::make('building')->label('Gedung')->maxLength(100), TextInput::make('floor')->label('Lantai')->numeric()->minValue(1), TextInput::make('capacity')->label('Kapasitas')->numeric()->minValue(1)->required(), Select::make('is_active')->label('Status')->options([1 => 'Aktif', 0 => 'Tidak Aktif'])->default(1)->required()]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([TextColumn::make('code')->label('Kode')->searchable()->sortable(), TextColumn::make('name')->label('Nama Ruang')->searchable(), TextColumn::make('building')->label('Gedung'), TextColumn::make('floor')->label('Lantai'), TextColumn::make('capacity')->label('Kapasitas')->sortable(), TextColumn::make('is_active')->label('Status')->formatStateUsing(fn($state) => $state ? 'Aktif' : 'Tidak Aktif')->badge()])->filters([SelectFilter::make('is_active')->label('Status')->options([1 => 'Aktif', 0 => 'Tidak Aktif'])])->actions([EditAction::make()->label('Ubah'), DeleteAction::make()->label('Hapus')->requiresConfirmation()]);
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ListRooms::route('/'), 'create' => Pages\CreateRoom::route('/create'), 'edit' => Pages\EditRoom::route('/{record}/edit')];
    }
}
