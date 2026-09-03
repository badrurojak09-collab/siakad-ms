<?php

namespace App\Filament\Resources\Graduations;

use App\Filament\Resources\Graduations\Pages;
use App\Models\Graduation;
use Filament\Actions\Action;
use Filament\Forms\Components\{DatePicker, Select, TextInput};
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\{Columns\TextColumn, Table};
use Filament\{Actions\DeleteAction, Actions\EditAction};
use BackedEnum;
use UnitEnum;

class GraduationResource extends Resource
{
    protected static ?string $slug = 'graduations';
    protected static ?string $model = Graduation::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAcademicCap;
    protected static string|UnitEnum|null $navigationGroup = 'Kelulusan';
    protected static ?string $navigationLabel = 'Kelulusan';
    protected static ?string $modelLabel = 'Kelulusan';
    protected static ?string $pluralModelLabel = 'Kelulusan';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('student_id')->label('Mahasiswa')->relationship('student', 'nim')->searchable()->preload()->required(),
            Select::make('semester_id')->label('Semester')->relationship('semester', 'id')->searchable()->preload()->required(),
            DatePicker::make('graduation_date')->label('Tanggal Lulus')->required(),
            Select::make('degree')->label('Gelar')->options(['A.Md.' => 'A.Md.', 'S.Tr.' => 'S.Tr.', 'S.Kom.' => 'S.Kom.', 'S.E.' => 'S.E.', 'S.Si.' => 'S.Si.', 'M.Kom.' => 'M.Kom.', 'M.M.' => 'M.M.', 'Dr.' => 'Dr.']),
            TextInput::make('gpa_final')->label('IPK Akhir')->numeric()->minValue(0)->maxValue(4)->step(0.01),
            Select::make('status')->label('Status')->options(['proposed' => 'Diajukan', 'eligible' => 'Memenuhi Syarat', 'approved' => 'Disetujui', 'rejected' => 'Ditolak', 'completed' => 'Selesai'])->default('proposed')->required(),
            TextInput::make('decree_number')->label('Nomor SK')->maxLength(100),
            DatePicker::make('decree_date')->label('Tanggal SK'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('student.nim')->label('NIM')->searchable(),
            TextColumn::make('student.user.name')->label('Mahasiswa')->searchable(),
            TextColumn::make('semester.id')->label('Semester'),
            TextColumn::make('degree')->label('Gelar'),
            TextColumn::make('gpa_final')->label('IPK')->sortable(),
            TextColumn::make('graduation_date')->label('Tanggal Lulus')->date(),
            TextColumn::make('status')->label('Status')->badge(),
            TextColumn::make('decree_number')->label('Nomor SK')->placeholder('-'),
        ])->actions([
            EditAction::make()->label('Ubah')->visible(fn(Graduation $record): bool => !in_array($record->status, ['approved', 'completed'], true)),
            Action::make('mark_eligible')->label('Tandai Memenuhi Syarat')->icon(Heroicon::OutlinedCheckCircle)->requiresConfirmation()->visible(fn(Graduation $record): bool => $record->status === 'proposed')->action(function (Graduation $record): void {
                $record->update(['status' => 'eligible']);
                Notification::make()->title('Status kelulusan diperbarui')->success()->send();
            }),
            DeleteAction::make()->label('Hapus')->visible(fn(Graduation $record): bool => $record->status === 'proposed')->requiresConfirmation(),
        ])->defaultSort('graduation_date', 'desc');
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ListGraduations::route('/'), 'create' => Pages\CreateGraduation::route('/create'), 'edit' => Pages\EditGraduation::route('/{record}/edit')];
    }
}
