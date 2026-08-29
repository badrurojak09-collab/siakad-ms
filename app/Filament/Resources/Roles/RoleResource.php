<?php

namespace App\Filament\Resources\Roles;

use App\Filament\Resources\Roles\Pages\{CreateRole, EditRole, ListRoles};
use Filament\Forms\Components\{Select, TextInput, Textarea};
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\{Columns\TextColumn, Table};
use Spatie\Permission\Models\Role;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use BackedEnum;
use UnitEnum;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShieldCheck;
    protected static string|UnitEnum|null $navigationGroup = 'Administrasi Sistem';
    protected static ?string $navigationLabel = 'Peran';
    protected static ?string $modelLabel = 'Peran';
    protected static ?string $pluralModelLabel = 'Peran';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')->label('Nama Peran')->required()->unique(ignoreRecord: true)->maxLength(100),
            TextInput::make('guard_name')->label('Guard')->default('web')->required()->maxLength(50),
            Select::make('permissions')->label('Hak Akses')->relationship('permissions', 'name')->multiple()->preload()->searchable(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('name')->label('Nama Peran')->searchable()->sortable(),
            TextColumn::make('guard_name')->label('Guard')->badge(),
            TextColumn::make('permissions_count')->label('Jumlah Hak Akses')->counts('permissions')->sortable(),
            TextColumn::make('created_at')->label('Dibuat Pada')->dateTime('d M Y H:i')->sortable(),
        ])->actions([
            EditAction::make()->label('Ubah'),
            DeleteAction::make()->label('Hapus')->requiresConfirmation(),
        ])->defaultSort('name');
    }

    public static function getPages(): array
    {
        return ['index' => ListRoles::route('/'), 'create' => CreateRole::route('/create'), 'edit' => EditRole::route('/{record}/edit')];
    }
}
