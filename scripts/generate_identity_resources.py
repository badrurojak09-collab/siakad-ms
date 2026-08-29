from pathlib import Path
root=Path('/home/ubuntu/siakad-laravel/app/Filament/Resources')

def write(rel, content):
    p=root/rel
    p.parent.mkdir(parents=True, exist_ok=True)
    p.write_text(content.strip()+"\n")

write('UserProfiles/UserProfileResource.php', r'''<?php
namespace App\Filament\Resources\UserProfiles;

use App\Models\User;
use App\Filament\Resources\UserProfiles\Pages\{CreateUserProfile, EditUserProfile, ListUserProfiles};
use Filament\Forms\Components\{Select, TextInput};
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\{Columns\TextColumn, Filters\SelectFilter, Table};
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
            TextInput::make('password')->label('Kata Sandi')->password()->revealable()->dehydrated(fn ($state) => filled($state))->required(fn (string $operation): bool => $operation === 'create')->minLength(8),
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
            \Filament\Tables\Actions\EditAction::make()->label('Ubah'),
            \Filament\Tables\Actions\DeleteAction::make()->label('Hapus')->requiresConfirmation(),
        ])->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return ['index' => ListUserProfiles::route('/'), 'create' => CreateUserProfile::route('/create'), 'edit' => EditUserProfile::route('/{record}/edit')];
    }
}
''')

write('Roles/RoleResource.php', r'''<?php
namespace App\Filament\Resources\Roles;

use App\Filament\Resources\Roles\Pages\{CreateRole, EditRole, ListRoles};
use Filament\Forms\Components\{Select, TextInput, Textarea};
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\{Columns\TextColumn, Table};
use Spatie\Permission\Models\Role;
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
            \Filament\Tables\Actions\EditAction::make()->label('Ubah'),
            \Filament\Tables\Actions\DeleteAction::make()->label('Hapus')->requiresConfirmation(),
        ])->defaultSort('name');
    }

    public static function getPages(): array
    {
        return ['index' => ListRoles::route('/'), 'create' => CreateRole::route('/create'), 'edit' => EditRole::route('/{record}/edit')];
    }
}
''')

write('Permissions/PermissionResource.php', r'''<?php
namespace App\Filament\Resources\Permissions;

use App\Filament\Resources\Permissions\Pages\{CreatePermission, EditPermission, ListPermissions};
use Filament\Forms\Components\{Select, TextInput};
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\{Columns\TextColumn, Table};
use Spatie\Permission\Models\Permission;
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
            \Filament\Tables\Actions\EditAction::make()->label('Ubah'),
            \Filament\Tables\Actions\DeleteAction::make()->label('Hapus')->requiresConfirmation(),
        ])->defaultSort('name');
    }

    public static function getPages(): array
    {
        return ['index' => ListPermissions::route('/'), 'create' => CreatePermission::route('/create'), 'edit' => EditPermission::route('/{record}/edit')];
    }
}
''')

pages={
'UserProfiles/Pages/ListUserProfiles.php':('ListUserProfiles','ListRecords','UserProfileResource'), 'UserProfiles/Pages/CreateUserProfile.php':('CreateUserProfile','CreateRecord','UserProfileResource'), 'UserProfiles/Pages/EditUserProfile.php':('EditUserProfile','EditRecord','UserProfileResource'),
'Roles/Pages/ListRoles.php':('ListRoles','ListRecords','RoleResource'), 'Roles/Pages/CreateRole.php':('CreateRole','CreateRecord','RoleResource'), 'Roles/Pages/EditRole.php':('EditRole','EditRecord','RoleResource'),
'Permissions/Pages/ListPermissions.php':('ListPermissions','ListRecords','PermissionResource'), 'Permissions/Pages/CreatePermission.php':('CreatePermission','CreateRecord','PermissionResource'), 'Permissions/Pages/EditPermission.php':('EditPermission','EditRecord','PermissionResource'),
}
for rel,(cls,base,res) in pages.items():
    ns=rel.split('/')[0]
    write(rel, f'''<?php
namespace App\\Filament\\Resources\\{ns}\\Pages;
use App\\Filament\\Resources\\{ns}\\{res};
use Filament\\Resources\\Pages\\{base};
class {cls} extends {base} {{ protected static string $resource = {res}::class; }}
''')
print('generated identity resources')
