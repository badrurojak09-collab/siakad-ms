<?php
namespace App\Filament\Resources\Lecturers;

use App\Models\Lecturer;
use App\Filament\Resources\Lecturers\Pages;
use Filament\Forms\Components\{DatePicker, Select, TextInput, Textarea};
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\{Actions\DeleteAction, Actions\EditAction, Columns\TextColumn, Filters\SelectFilter, Table};
use BackedEnum;
use UnitEnum;

class LecturerResource extends Resource
{
    protected static ?string $model = Lecturer::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUser;
    protected static string|UnitEnum|null $navigationGroup = 'Data Akademik';
    protected static ?string $navigationLabel = 'Dosen';
    protected static ?string $modelLabel = 'Dosen';
    protected static ?string $pluralModelLabel = 'Dosen';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('user_id')->label('Akun Pengguna')->relationship('user', 'name')->searchable()->preload()->nullable(),
            Select::make('study_program_id')->label('Program Studi')->relationship('studyProgram', 'name')->searchable()->preload()->nullable(),
            TextInput::make('nidn')->label('NIDN')->required()->unique(ignoreRecord: true)->maxLength(30),
            TextInput::make('academic_rank')->label('Jabatan Akademik')->maxLength(100),
            DatePicker::make('join_date')->label('Tanggal Bergabung')->native(false),
            Textarea::make('specialization')->label('Bidang Keahlian')->columnSpanFull(),
        ]);
    }
    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('nidn')->label('NIDN')->searchable()->sortable(),
            TextColumn::make('user.name')->label('Nama Dosen')->searchable()->sortable(),
            TextColumn::make('studyProgram.name')->label('Program Studi')->searchable(),
            TextColumn::make('academic_rank')->label('Jabatan Akademik')->badge(),
            TextColumn::make('join_date')->label('Tanggal Bergabung')->date('d M Y'),
        ])->actions([EditAction::make()->label('Ubah'), DeleteAction::make()->label('Hapus')->requiresConfirmation()])->defaultSort('nidn');
    }
    public static function getPages(): array { return ['index' => Pages\ListLecturers::route('/'), 'create' => Pages\CreateLecturer::route('/create'), 'edit' => Pages\EditLecturer::route('/{record}/edit')]; }
}
