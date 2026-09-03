<?php

namespace App\Filament\Resources\Faculties\Schemas;

use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class FacultyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('tenant_id')
                    ->relationship('tenant', 'name'),
                TextInput::make('code')
                    ->required(),
                TextInput::make('name')
                    ->required(),
                Select::make('dean_id')
                    ->label('Dekan Fakultas')
                    ->relationship(
                        name: 'dean',
                        titleAttribute: 'id',
                        // 🚀 Optimasi Eager Loading (Cegah N+1 / Lazy Loading)
                        modifyQueryUsing: fn(Builder $query) => $query->with('user')
                    )
                    ->getOptionLabelFromRecordUsing(fn($record) => $record->user?->name ?? 'Tanpa Nama')
                    ->searchable(['users.name'])  // Nembak langsung ke tabel users
                    ->preload()
                    ->nullable(),
                KeyValue::make('metadata')
                    ->label('Metadata / Data Tambahan')
                    ->keyLabel('Nama Parameter')  // contoh: sk_number
                    ->valueLabel('Nilai')  // contoh: SK/2026/001
                    ->reorderable()
                    ->columnSpanFull(),
            ]);
    }
}
