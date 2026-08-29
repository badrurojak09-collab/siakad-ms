<?php

namespace App\Filament\Resources\Tenants;

use App\Enums\TenantStatus;
use App\Filament\Resources\Tenants\Pages;
use App\Filament\Resources\Tenants\RelationManagers\UsersRelationManager;
use App\Models\Tenant;
use Filament\Forms\Components\{DatePicker, Select, TextInput, Textarea};
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\{Columns\TextColumn, Filters\SelectFilter, Table};
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use BackedEnum;
use UnitEnum;

class TenantResource extends Resource
{
    protected static ?string $model = Tenant::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice2;
    protected static string|UnitEnum|null $navigationGroup = 'Administrasi Sistem';
    protected static ?string $navigationLabel = 'Institusi';
    protected static ?string $modelLabel = 'Institusi';
    protected static ?string $pluralModelLabel = 'Institusi';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')->label('Nama Institusi')->required()->maxLength(255),
            TextInput::make('code')->label('Kode Institusi')->required()->alphaDash()->uppercase()->unique(ignoreRecord: true)->maxLength(50),
            TextInput::make('domain')->label('Domain')->url()->nullable()->unique(ignoreRecord: true)->maxLength(255),
            Select::make('status')->label('Status')->options(collect(TenantStatus::cases())->mapWithKeys(fn(TenantStatus $status): array => [$status->value => $status->label()]))->default(TenantStatus::Trial->value)->required(),
            TextInput::make('subscription_plan')->label('Paket Berlangganan')->default('trial')->required()->maxLength(50),
            DatePicker::make('subscription_expiry')->label('Berakhir Berlangganan')->native(false),
            TextInput::make('max_students')->label('Batas Mahasiswa')->numeric()->minValue(0)->default(0)->helperText('Isi 0 untuk tanpa batas.'),
            TextInput::make('max_lecturers')->label('Batas Dosen')->numeric()->minValue(0)->default(0)->helperText('Isi 0 untuk tanpa batas.'),
            Textarea::make('config')->label('Konfigurasi JSON')->helperText('Opsional. Masukkan JSON valid.')->formatStateUsing(fn($state) => is_array($state) ? json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : $state)->dehydrateStateUsing(function ($state) {
                if (blank($state)) return null;
                $decoded = json_decode($state, true);
                if (json_last_error() !== JSON_ERROR_NONE) throw new \InvalidArgumentException('Konfigurasi harus berupa JSON yang valid.');
                return $decoded;
            })->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('code')->label('Kode')->searchable()->sortable(),
            TextColumn::make('name')->label('Nama Institusi')->searchable()->sortable(),
            TextColumn::make('domain')->label('Domain')->placeholder('-'),
            TextColumn::make('status')->label('Status')->badge(),
            TextColumn::make('subscription_plan')->label('Paket')->badge(),
            TextColumn::make('users_count')->label('Jumlah Pengguna')->counts('users')->sortable(),
            TextColumn::make('subscription_expiry')->label('Berakhir')->date('d M Y')->placeholder('Tidak dibatasi')->sortable(),
            TextColumn::make('updated_at')->label('Diperbarui Pada')->dateTime('d M Y H:i')->sortable(),
        ])->filters([
            SelectFilter::make('status')->label('Status')->options(collect(TenantStatus::cases())->mapWithKeys(fn(TenantStatus $status): array => [$status->value => $status->label()])),
            SelectFilter::make('subscription_plan')->label('Paket Berlangganan'),
        ])->actions([
            EditAction::make()->label('Ubah')->visible(fn(Tenant $record): bool => auth()->user()?->hasAnyRole(['platform_superadmin', 'superadmin', 'admin']) ?? false),
            DeleteAction::make()->label('Hapus')->requiresConfirmation()->visible(fn(Tenant $record): bool => auth()->user()?->hasAnyRole(['platform_superadmin', 'superadmin']) && $record->status === TenantStatus::Inactive),
        ])->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [UsersRelationManager::class];
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ListTenants::route('/'), 'create' => Pages\CreateTenant::route('/create'), 'edit' => Pages\EditTenant::route('/{record}/edit')];
    }
}
