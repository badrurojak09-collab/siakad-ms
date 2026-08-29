<?php

namespace App\Filament\Resources\KrsDetails;

use App\Models\KrsDetail;
use App\Filament\Resources\KrsDetails\Pages;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\{Actions\DeleteAction, Actions\EditAction, Columns\TextColumn, Table};
use BackedEnum;
use UnitEnum;

class KrsDetailResource extends Resource
{
    protected static ?string $slug = 'krs-details';
    protected static ?string $model = KrsDetail::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedListBullet;
    protected static string|UnitEnum|null $navigationGroup = 'KRS & Registrasi';
    protected static ?string $navigationLabel = 'Detail KRS';
    protected static ?string $modelLabel = 'Detail KRS';
    protected static ?string $pluralModelLabel = 'Detail KRS';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('krs_header_id')->label('KRS')->relationship('krsHeader', 'id')->searchable()->preload()->required(),
            Select::make('course_class_id')->label('Kelas Perkuliahan')->relationship('courseClass', 'class_code')->searchable()->preload()->required(),
            Select::make('status')->label('Status')->options(['registered' => 'Terdaftar', 'dropped' => 'Dibatalkan'])->default('registered')->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('krsHeader.student.nim')->label('NIM')->searchable(),
            TextColumn::make('courseClass.course.code')->label('Kode Mata Kuliah'),
            TextColumn::make('courseClass.course.name')->label('Mata Kuliah'),
            TextColumn::make('courseClass.class_code')->label('Kelas'),
            TextColumn::make('status')->label('Status')->badge(),
            TextColumn::make('registered_at')->label('Terdaftar Pada')->dateTime('d M Y H:i'),
        ])->actions([
            EditAction::make()->label('Ubah')->visible(fn (KrsDetail $record): bool => $record->status === 'registered'),
            DeleteAction::make()->label('Hapus')->visible(fn (KrsDetail $record): bool => $record->status === 'registered')->requiresConfirmation(),
        ]);
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ListKrsDetails::route('/'), 'create' => Pages\CreateKrsDetail::route('/create'), 'edit' => Pages\EditKrsDetail::route('/{record}/edit')];
    }
}
