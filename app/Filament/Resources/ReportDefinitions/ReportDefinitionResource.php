<?php

namespace App\Filament\Resources\ReportDefinitions;

use App\Models\ReportDefinition;
use App\Filament\Resources\ReportDefinitions\Pages;
use Filament\Forms\Components\{Select,Textarea,TextInput};
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\{Actions\DeleteAction,Actions\EditAction,Columns\TextColumn,Table};
use BackedEnum;
use UnitEnum;

class ReportDefinitionResource extends Resource
{
    protected static ?string $slug = 'report-definitions';
    protected static ?string $model = ReportDefinition::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentChartBar;
    protected static string|UnitEnum|null $navigationGroup = 'Pelaporan Akademik';
    protected static ?string $navigationLabel = 'Definisi Laporan';
    protected static ?string $modelLabel = 'Definisi Laporan';
    protected static ?string $pluralModelLabel = 'Definisi Laporan';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')->label('Nama Laporan')->required()->maxLength(150),
            Select::make('category')->label('Kategori')->options(['akademik'=>'Akademik','keuangan'=>'Keuangan','pmb'=>'PMB','kemahasiswaan'=>'Kemahasiswaan','pddikti'=>'PDDikti'])->required(),
            Textarea::make('query_template')->label('Template Query')->rows(8)->helperText('Hanya administrator berwenang yang boleh mengubah template query.'),
            Textarea::make('parameters')->label('Parameter JSON')->json()->helperText('Masukkan parameter default dalam format JSON.'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('name')->label('Nama Laporan')->searchable(),
            TextColumn::make('category')->label('Kategori')->badge(),
            TextColumn::make('created_by')->label('Dibuat Oleh')->placeholder('-'),
            TextColumn::make('created_at')->label('Dibuat Pada')->dateTime('d M Y H:i')->sortable(),
        ])->actions([EditAction::make()->label('Ubah'), DeleteAction::make()->label('Hapus')->requiresConfirmation()])->defaultSort('created_at','desc');
    }

    public static function getPages(): array { return ['index'=>Pages\ListReportDefinitions::route('/'),'create'=>Pages\CreateReportDefinition::route('/create'),'edit'=>Pages\EditReportDefinition::route('/{record}/edit')]; }
}
