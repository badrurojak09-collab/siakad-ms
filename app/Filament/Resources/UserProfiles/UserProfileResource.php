<?php

namespace App\Filament\Resources\UserProfiles;

use App\Models\User;
use App\Filament\Resources\UserProfiles\Pages\{CreateUserProfile, EditUserProfile, ListUserProfiles};
use Filament\Forms\Components\{Select, TextInput};
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\{Columns\TextColumn, Filters\SelectFilter, Table};
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use BackedEnum;
use UnitEnum;

class UserProfileResource extends Resource
{
    protected static ?string $model = User::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;
    protected static string|UnitEnum|null $navigationGroup = 'Administrasi Sistem';
    protected static ?string $navigationLabel = 'Profil Pengguna';
    protected static ?string $modelLabel = 'Profil Pengguna';
    protected static ?string $pluralModelLabel = 'Profil Pengguna';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')->label('Nama Lengkap')->required()->maxLength(255),
            TextInput::make('email')->label('Surel')->email()->required()->unique(ignoreRecord: true)->maxLength(255),
            TextInput::make('password')->label('Kata Sandi')->password()->revealable()->dehydrated(fn($state) => filled($state))->required(fn(string $operation): bool => $operation === 'create')->minLength(8),
            Select::make('roles')->label('Peran')->relationship('roles', 'name')->multiple()->preload()->searchable()->required(),
            Select::make('tenants')->label('Institusi / Tenant')->relationship('tenants', 'name')->multiple()->preload()->searchable()->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('name')->label('Nama Lengkap')->searchable()->sortable(),
            TextColumn::make('email')->label('Surel')->searchable(),
            TextColumn::make('roles.name')->label('Peran')->badge()->separator(', '),
            TextColumn::make('tenants.name')->label('Tenant')->badge()->separator(', '),
            TextColumn::make('email_verified_at')->label('Surel Terverifikasi')->dateTime('d M Y H:i')->placeholder('Belum'),
            TextColumn::make('created_at')->label('Dibuat Pada')->dateTime('d M Y H:i')->sortable(),
        ])->filters([
            SelectFilter::make('roles')->label('Peran')->relationship('roles', 'name'),
        ])->actions([
            EditAction::make()->label('Ubah'),
            DeleteAction::make()->label('Hapus')->requiresConfirmation(),
        ])->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return ['index' => ListUserProfiles::route('/'), 'create' => CreateUserProfile::route('/create'), 'edit' => EditUserProfile::route('/{record}/edit')];
    }
}
