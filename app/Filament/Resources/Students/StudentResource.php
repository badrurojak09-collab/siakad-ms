<?php

namespace App\Filament\Resources\Students;

use App\Filament\Resources\Concerns\ScopesOwnStudentRecords;
use App\Filament\Resources\Students\Tables\StudentsTable;
use App\Filament\Resources\Students\Pages;
use App\Models\Student;
use App\Services\TenantContext;
use Filament\Forms\Components\{DatePicker, Select, TextInput, Textarea};
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\Rules\Unique;
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

    /**
     * EAGER LOADING
     * Me-load relasi 'user' dan 'studyProgram' secara otomatis
     * untuk mencegah N+1 Query saat menampilkan tabel.
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['user', 'studyProgram']);
    }

    public static function form(Schema $schema): Schema
    {
        // Mengambil ID Tenant Aktif melalui Service TenantContext
        $tenantId = app(TenantContext::class)->id();

        return $schema->components([
            Section::make('Informasi Mahasiswa')
                ->description('Data identitas Mahasiswa')
                ->schema([
                    Select::make('user_id')
                        ->label('Akun Pengguna')
                        ->relationship(
                            name: 'user',
                            titleAttribute: 'name',
                            modifyQueryUsing: function (Builder $query, $record) use ($tenantId) {
                                return $query
                                    // 1. User harus terikat dengan tenant saat ini & dalam kondisi aktif
                                    ->whereHas('tenants', function ($q) use ($tenantId) {
                                        $q
                                            ->where('tenants.id', $tenantId)
                                            ->where('tenant_user.is_active', true);
                                    })
                                    // 2. User belum terikat ke mahasiswa manapun
                                    ->whereDoesntHave('student')
                                    // 3. Jika sedang EDIT, sertakan kembali user yang sedang terpasang pada record ini
                                    ->when($record?->user_id, fn($q) => $q->orWhere('users.id', $record->user_id));
                            }
                        )
                        ->searchable()
                        ->preload()
                        ->nullable()
                        // MENCEGAH DUPLIKASI USER_ID DI TABEL STUDENTS
                        ->unique(
                            table: 'students',
                            column: 'user_id',
                            ignoreRecord: true,
                            modifyRuleUsing: function (Unique $rule) use ($tenantId) {
                                return $rule->where('tenant_id', $tenantId);
                            }
                        )
                        ->validationMessages([
                            'unique' => 'User ini sudah terikat dengan mahasiswa lain.',
                        ]),
                    Select::make('study_program_id')
                        ->label('Program Studi')
                        ->relationship(
                            name: 'studyProgram',
                            titleAttribute: 'name',
                            modifyQueryUsing: function (Builder $query) use ($tenantId) {
                                // Filter Program Studi agar sesuai dengan tenant yang sedang aktif
                                return $query->where('tenant_id', $tenantId);
                            }
                        )
                        ->searchable()
                        ->preload()
                        ->required(),
                    TextInput::make('nim')
                        ->label('NIM')
                        ->required()
                        ->unique(
                            table: 'students',
                            column: 'nim',
                            ignoreRecord: true,
                            modifyRuleUsing: fn(Unique $rule) => $rule->where('tenant_id', $tenantId)
                        )
                        ->maxLength(50),
                    TextInput::make('entry_year')
                        ->label('Tahun Masuk')
                        ->numeric()
                        ->minValue(1900)
                        ->maxValue((int) date('Y') + 1)
                        ->required(),
                    Select::make('entry_semester')
                        ->label('Semester Masuk')
                        ->options([1 => 'Ganjil', 2 => 'Genap'])
                        ->nullable(),
                    Select::make('admission_type')
                        ->label('Jalur Masuk')
                        ->options([
                            'regular' => 'Reguler',
                            'transfer' => 'Transfer',
                            'achievement' => 'Prestasi'
                        ])
                        ->nullable(),
                    Textarea::make('address')
                        ->label('Alamat')
                        ->columnSpanFull(),
                    TextInput::make('phone')
                        ->label('Telepon')
                        ->tel()
                        ->maxLength(30),
                    Select::make('status')
                        ->label('Status')
                        ->options([
                            'active' => 'Aktif',
                            'inactive' => 'Tidak Aktif',
                            'graduated' => 'Lulus',
                            'dropped' => 'Mengundurkan Diri',
                            'leave' => 'Cuti'
                        ])
                        ->default('active')
                        ->required(),
                ])
                ->columns(2)
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return StudentsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'edit' => Pages\EditStudent::route('/{record}/edit')
        ];
    }
}
