<?php

namespace App\Filament\Resources\GeneratedReports;

use App\Models\GeneratedReport;
use App\Filament\Resources\GeneratedReports\Pages;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use BackedEnum;
use UnitEnum;

class GeneratedReportResource extends Resource
{
    protected static ?string $slug = 'generated-reports';
    protected static ?string $model = GeneratedReport::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentArrowDown;
    protected static string|UnitEnum|null $navigationGroup = 'Pelaporan Akademik';
    protected static ?string $navigationLabel = 'Laporan Tersedia';
    protected static ?string $modelLabel = 'Laporan Tersedia';
    protected static ?string $pluralModelLabel = 'Laporan Tersedia';
    public static function canCreate(): bool { return false; }
    public static function form(Schema $schema): Schema { return $schema->components([]); }
    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('definition.name')->label('Definisi Laporan')->searchable(),
            TextColumn::make('file_format')->label('Format')->badge(),
            TextColumn::make('file_url')->label('File')->url(fn($state) => $state, true)->limit(70),
            TextColumn::make('generated_by')->label('Dibuat Oleh')->placeholder('-'),
            TextColumn::make('generated_at')->label('Dibuat Pada')->dateTime('d M Y H:i')->sortable(),
            TextColumn::make('expiry_date')->label('Berlaku Sampai')->dateTime('d M Y H:i'),
        ])->defaultSort('generated_at','desc');
    }
    public static function getPages(): array { return ['index'=>Pages\ListGeneratedReports::route('/')]; }
}
