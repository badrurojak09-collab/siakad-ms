<?php
namespace App\Filament\Resources\Students;

use App\Models\Student;
use App\Filament\Resources\Concerns\ScopesOwnStudentRecords;
use App\Filament\Resources\Students\Pages;
use Filament\Forms\Components\{DatePicker, Select, TextInput, Textarea};
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\{Actions\DeleteAction, Actions\EditAction, Columns\TextColumn, Filters\SelectFilter, Table};
use BackedEnum;
use UnitEnum;

class StudentResource extends Resource
{
    use ScopesOwnStudentRecords;
    protected static ?string $model = Student::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAcademicCap;
    protected static string|UnitEnum|null $navigationGroup = 'Data Akademik';
    protected static ?string $navigationLabel = 'Mahasiswa';
    protected static ?string $modelLabel = 'Mahasiswa';
    protected static ?string $pluralModelLabel = 'Mahasiswa';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('user_id')->label('Akun Pengguna')->relationship('user', 'name')->searchable()->preload()->nullable(),
            Select::make('study_program_id')->label('Program Studi')->relationship('studyProgram', 'name')->searchable()->preload()->required(),
            TextInput::make('nim')->label('NIM')->required()->unique(ignoreRecord: true)->maxLength(50),
            TextInput::make('entry_year')->label('Tahun Masuk')->numeric()->minValue(1900)->maxValue((int) date('Y') + 1)->required(),
            Select::make('entry_semester')->label('Semester Masuk')->options([1 => 'Ganjil', 2 => 'Genap'])->nullable(),
            Select::make('admission_type')->label('Jalur Masuk')->options(['regular' => 'Reguler', 'transfer' => 'Transfer', 'achievement' => 'Prestasi'])->nullable(),
            Textarea::make('address')->label('Alamat')->columnSpanFull(),
            TextInput::make('phone')->label('Telepon')->tel()->maxLength(30),
            Select::make('status')->label('Status')->options(['active' => 'Aktif', 'inactive' => 'Tidak Aktif', 'graduated' => 'Lulus', 'dropped' => 'Mengundurkan Diri', 'leave' => 'Cuti'])->default('active')->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('nim')->label('NIM')->searchable()->sortable(),
            TextColumn::make('user.name')->label('Nama Mahasiswa')->searchable()->sortable(),
            TextColumn::make('studyProgram.name')->label('Program Studi')->searchable(),
            TextColumn::make('entry_year')->label('Tahun Masuk')->sortable(),
            TextColumn::make('admission_type')->label('Jalur Masuk')->badge(),
            TextColumn::make('status')->label('Status')->badge(),
        ])->filters([SelectFilter::make('status')->label('Status')->options(['active' => 'Aktif', 'inactive' => 'Tidak Aktif', 'graduated' => 'Lulus', 'dropped' => 'Mengundurkan Diri', 'leave' => 'Cuti'])])->actions([
            EditAction::make()->label('Ubah'), DeleteAction::make()->label('Hapus')->requiresConfirmation(),
        ])->defaultSort('nim');
    }
    public static function getPages(): array { return ['index' => Pages\ListStudents::route('/'), 'create' => Pages\CreateStudent::route('/create'), 'edit' => Pages\EditStudent::route('/{record}/edit')]; }
}
