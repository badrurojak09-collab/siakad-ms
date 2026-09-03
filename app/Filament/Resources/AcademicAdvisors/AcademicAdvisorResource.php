<?php

namespace App\Filament\Resources\AcademicAdvisors;

use App\Models\AcademicAdvisor;
use App\Models\Semester;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\{DatePicker, Select, Toggle};
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use BackedEnum;
use UnitEnum;

class AcademicAdvisorResource extends Resource
{
    protected static ?string $slug = 'academic-advisors';
    protected static ?string $model = AcademicAdvisor::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAcademicCap;
    protected static string|UnitEnum|null $navigationGroup = 'Data Akademik';
    protected static ?string $navigationLabel = 'Pembimbing Akademik';
    protected static ?string $modelLabel = 'Pembimbing Akademik';
    protected static ?string $pluralModelLabel = 'Pembimbing Akademik';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Penetapan Pembimbing')
                ->description('Tentukan mahasiswa dan dosen pembimbing akademik pada tenant aktif.')
                ->icon(Heroicon::OutlinedUserGroup)
                ->columns(2)
                ->schema([
                    Select::make('student_id')
                        ->label('Mahasiswa')
                        ->relationship('student', 'nim')
                        ->searchable(['nim'])
                        ->preload()
                        ->required(),
                    Select::make('lecturer_id')
                        ->label('Dosen Pembimbing')
                        ->relationship('lecturer', 'nidn')
                        ->searchable(['nidn'])
                        ->preload()
                        ->required(),
                ]),
            Section::make('Periode Akademik')
                ->description('Periode penugasan pembimbing akademik.')
                ->icon(Heroicon::OutlinedCalendarDays)
                ->columns(2)
                ->schema([
                    Select::make('semester_id')
                        ->label('Semester')
                        ->relationship('semester', 'semester_type')
                        ->getOptionLabelFromRecordUsing(fn(Semester $record): string => self::semesterLabel($record))
                        ->searchable(['semester_type'])
                        ->preload()
                        ->required(),
                    DatePicker::make('assigned_date')
                        ->label('Tanggal Penetapan')
                        ->native(false)
                        ->default(now())
                        ->required(),
                ]),
            Section::make('Status Penugasan')
                ->description('Nonaktifkan penugasan lama sebelum menetapkan pembimbing pengganti.')
                ->icon(Heroicon::OutlinedShieldCheck)
                ->schema([
                    Toggle::make('is_active')
                        ->label('Penugasan Aktif')
                        ->default(true)
                        ->required(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('student.nim')->label('NIM')->searchable()->sortable(),
                TextColumn::make('student.user.name')->label('Mahasiswa')->searchable(),
                TextColumn::make('lecturer.nidn')->label('NIDN')->searchable(),
                TextColumn::make('lecturer.user.name')->label('Dosen Pembimbing')->searchable(),
                TextColumn::make('semester.semester_type')
                    ->label('Semester')
                    ->formatStateUsing(fn(?string $state): string => self::semesterTypeLabel($state))
                    ->sortable(),
                TextColumn::make('assigned_date')->label('Tanggal Penetapan')->date('d M Y')->sortable(),
                TextColumn::make('is_active')->label('Status')->badge()->formatStateUsing(fn(bool $state): string => $state ? 'Aktif' : 'Tidak Aktif')->color(fn(bool $state): string => $state ? 'success' : 'gray'),
            ])
            ->filters([
                SelectFilter::make('is_active')->label('Status')->options([1 => 'Aktif', 0 => 'Tidak Aktif']),
                SelectFilter::make('semester_id')
                    ->label('Semester')
                    ->options(fn(): array => Semester::query()
                        ->orderByDesc('start_date')
                        ->get()
                        ->mapWithKeys(fn(Semester $semester): array => [
                            $semester->id => self::semesterLabel($semester),
                        ])
                        ->all()),
            ])
            ->actions([
                EditAction::make()->label('Ubah')->visible(fn(AcademicAdvisor $record): bool => $record->is_active),
                DeleteAction::make()->label('Hapus')->requiresConfirmation()->visible(fn(AcademicAdvisor $record): bool => !$record->is_active),
            ])
            ->defaultSort('assigned_date', 'desc');
    }

    private static function semesterTypeLabel(?string $type): string
    {
        return match ($type) {
            'odd', 'ganjil' => 'Ganjil',
            'even', 'genap' => 'Genap',
            default => (string) ($type ?? '-'),
        };
    }

    private static function semesterLabel(Semester $semester): string
    {
        $start = $semester->start_date?->format('d/m/Y') ?? '-';
        $end = $semester->end_date?->format('d/m/Y') ?? '-';

        return sprintf('%s (%s - %s)', self::semesterTypeLabel($semester->semester_type), $start, $end);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAcademicAdvisors::route('/'),
            'create' => Pages\CreateAcademicAdvisor::route('/create'),
            'edit' => Pages\EditAcademicAdvisor::route('/{record}/edit'),
        ];
    }
}
