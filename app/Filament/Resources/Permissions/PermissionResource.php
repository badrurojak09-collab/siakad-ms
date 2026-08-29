<?php

namespace App\Filament\Resources\Permissions;

use App\Filament\Resources\Permissions\Pages\{CreatePermission, EditPermission, ListPermissions};
use Filament\Forms\Components\{Select, TextInput};
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\{Columns\TextColumn, Table};
use Spatie\Permission\Models\Permission;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use BackedEnum;
use UnitEnum;

class PermissionResource extends Resource
{
    protected static ?string $model = Permission::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedKey;
    protected static string|UnitEnum|null $navigationGroup = 'Administrasi Sistem';
    protected static ?string $navigationLabel = 'Hak Akses';
    protected static ?string $modelLabel = 'Hak Akses';
    protected static ?string $pluralModelLabel = 'Hak Akses';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')->label('Nama Hak Akses')->helperText('Contoh: academic.view atau krs.manage')->required()->unique(ignoreRecord: true)->maxLength(150),
            TextInput::make('guard_name')->label('Guard')->default('web')->required()->maxLength(50),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('name')->label('Nama Hak Akses')->searchable()->sortable(),
            TextColumn::make('guard_name')->label('Guard')->badge(),
            TextColumn::make('roles_count')->label('Digunakan Oleh')->counts('roles')->sortable(),
            TextColumn::make('created_at')->label('Dibuat Pada')->dateTime('d M Y H:i')->sortable(),
        ])->actions([
            EditAction::make()->label('Ubah'),
            DeleteAction::make()->label('Hapus')->requiresConfirmation(),
        ])->defaultSort('name');
    }

    public static function getPages(): array
    {
        return ['index' => ListPermissions::route('/'), 'create' => CreatePermission::route('/create'), 'edit' => EditPermission::route('/{record}/edit')];
    }
}
