<?php

namespace App\Filament\Resources\KrsLogs;

use App\Models\KrsLog;
use App\Filament\Clusters\KrsCluster;
use App\Filament\Resources\KrsLogs\Pages;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class KrsLogResource extends Resource
{
    protected static ?string $slug = 'krs-logs';
    protected static ?string $model = KrsLog::class;
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedClock;
    protected static ?string $cluster = KrsCluster::class;
    protected static ?string $navigationLabel = 'Riwayat KRS';
    protected static ?string $modelLabel = 'Riwayat KRS';
    protected static ?string $pluralModelLabel = 'Riwayat KRS';
    protected static ?int $navigationSort = 3;
    public static function canCreate(): bool
    {
        return false;
    }
    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }
    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('krsHeader.student.nim')
                ->label('NIM')->searchable(),
            TextColumn::make('previous_status')
                ->label('Status Sebelumnya')->badge(),
            TextColumn::make('new_status')
                ->label('Status Baru')->badge(),
            TextColumn::make('changedBy.name')
                ->label('Diubah Oleh')->placeholder('-'),
            TextColumn::make('changed_at')
                ->label('Waktu Perubahan')->dateTime('d M Y H:i')->sortable(),
            TextColumn::make('reason')
                ->label('Alasan')->limit(80),
        ])->paginated([10, 25, 50]);
    }
    public static function getPages(): array
    {
        return ['index' => Pages\ListKrsLogs::route('/')];
    }
}
