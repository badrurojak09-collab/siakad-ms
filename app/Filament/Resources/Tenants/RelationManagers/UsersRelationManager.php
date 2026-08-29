<?php

namespace App\Filament\Resources\Tenants\RelationManagers;

use App\Models\User;
use Filament\Forms\Components\{DateTimePicker, Toggle};
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\{Actions\AttachAction, Actions\DetachAction, Actions\EditAction, Columns\TextColumn, Table};

class UsersRelationManager extends RelationManager
{
    protected static string $relationship = 'users';
    protected static ?string $title = 'Pengguna Institusi';
    protected static ?string $recordTitleAttribute = 'name';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Toggle::make('is_active')->label('Membership Aktif')->default(true),
            DateTimePicker::make('joined_at')->label('Bergabung Pada')->default(now())->native(false),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table->recordTitleAttribute('name')->columns([
            TextColumn::make('name')->label('Nama')->searchable()->sortable(),
            TextColumn::make('email')->label('Surel')->searchable(),
            TextColumn::make('pivot.is_active')->label('Status Membership')->formatStateUsing(fn ($state): string => $state ? 'Aktif' : 'Tidak Aktif')->badge(),
            TextColumn::make('pivot.joined_at')->label('Bergabung Pada')->dateTime('d M Y H:i'),
        ])->headerActions([
            AttachAction::make()->label('Tambahkan Pengguna')->preloadRecordSelect()->recordSelectSearchColumns(['name', 'email'])->form([
                Toggle::make('is_active')->label('Membership Aktif')->default(true),
                DateTimePicker::make('joined_at')->label('Bergabung Pada')->default(now())->native(false),
            ]),
        ])->actions([
            EditAction::make()->label('Ubah Membership'),
            DetachAction::make()->label('Lepas dari Institusi')->requiresConfirmation(),
        ]);
    }
}
