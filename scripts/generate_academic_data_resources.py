from pathlib import Path
root=Path('/home/ubuntu/siakad-laravel/app/Filament/Resources')
def write(rel,text):
 p=root/rel; p.parent.mkdir(parents=True,exist_ok=True); p.write_text(text.strip()+'\n')
write('Students/StudentResource.php',r'''<?php
namespace App\Filament\Resources\Students;

use App\Models\Student;
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
''')
write('Lecturers/LecturerResource.php',r'''<?php
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
''')
write('Courses/CourseResource.php',r'''<?php
namespace App\Filament\Resources\Courses;

use App\Models\Course;
use App\Filament\Resources\Courses\Pages;
use Filament\Forms\Components\{Select, TextInput, Textarea};
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\{Actions\DeleteAction, Actions\EditAction, Columns\TextColumn, Table};
use BackedEnum;
use UnitEnum;

class CourseResource extends Resource
{
    protected static ?string $model = Course::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBookOpen;
    protected static string|UnitEnum|null $navigationGroup = 'Kurikulum & Mata Kuliah';
    protected static ?string $navigationLabel = 'Mata Kuliah';
    protected static ?string $modelLabel = 'Mata Kuliah';
    protected static ?string $pluralModelLabel = 'Mata Kuliah';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('code')->label('Kode Mata Kuliah')->required()->unique(ignoreRecord: true)->maxLength(30),
            TextInput::make('name')->label('Nama Mata Kuliah')->required()->maxLength(255),
            TextInput::make('credits')->label('Total SKS')->numeric()->minValue(0)->maxValue(20)->required(),
            TextInput::make('theory_credits')->label('SKS Teori')->numeric()->minValue(0)->maxValue(20)->default(0)->required(),
            TextInput::make('practice_credits')->label('SKS Praktik')->numeric()->minValue(0)->maxValue(20)->default(0)->required(),
            Select::make('course_type')->label('Jenis Mata Kuliah')->options(['mandatory' => 'Wajib', 'elective' => 'Pilihan', 'general' => 'Umum'])->nullable(),
            Textarea::make('description')->label('Deskripsi')->columnSpanFull(),
        ]);
    }
    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('code')->label('Kode')->searchable()->sortable(), TextColumn::make('name')->label('Nama Mata Kuliah')->searchable()->sortable(), TextColumn::make('credits')->label('SKS')->sortable(), TextColumn::make('theory_credits')->label('SKS Teori'), TextColumn::make('practice_credits')->label('SKS Praktik'), TextColumn::make('course_type')->label('Jenis')->badge(),
        ])->actions([EditAction::make()->label('Ubah'), DeleteAction::make()->label('Hapus')->requiresConfirmation()])->defaultSort('code');
    }
    public static function getPages(): array { return ['index' => Pages\ListCourses::route('/'), 'create' => Pages\CreateCourse::route('/create'), 'edit' => Pages\EditCourse::route('/{record}/edit')]; }
}
''')
write('CoursePrerequisites/CoursePrerequisiteResource.php',r'''<?php
namespace App\Filament\Resources\CoursePrerequisites;

use App\Models\CoursePrerequisite;
use App\Filament\Resources\CoursePrerequisites\Pages;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\{Actions\DeleteAction, Actions\EditAction, Columns\IconColumn, Columns\TextColumn, Table};
use BackedEnum;
use UnitEnum;

class CoursePrerequisiteResource extends Resource
{
    protected static ?string $model = CoursePrerequisite::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedLink;
    protected static string|UnitEnum|null $navigationGroup = 'Kurikulum & Mata Kuliah';
    protected static ?string $navigationLabel = 'Prasyarat Mata Kuliah';
    protected static ?string $modelLabel = 'Prasyarat Mata Kuliah';
    protected static ?string $pluralModelLabel = 'Prasyarat Mata Kuliah';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('course_id')->label('Mata Kuliah')->relationship('course', 'name')->searchable()->preload()->required(),
            Select::make('prerequisite_course_id')->label('Mata Kuliah Prasyarat')->relationship('prerequisiteCourse', 'name')->searchable()->preload()->required()->different('course_id'),
            Select::make('is_mandatory')->label('Sifat Prasyarat')->options([1 => 'Wajib', 0 => 'Opsional'])->default(1)->required(),
        ]);
    }
    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('course.code')->label('Kode Mata Kuliah')->searchable(), TextColumn::make('course.name')->label('Mata Kuliah')->searchable(), TextColumn::make('prerequisiteCourse.code')->label('Kode Prasyarat')->searchable(), TextColumn::make('prerequisiteCourse.name')->label('Mata Kuliah Prasyarat')->searchable(), IconColumn::make('is_mandatory')->label('Wajib')->boolean(),
        ])->actions([EditAction::make()->label('Ubah'), DeleteAction::make()->label('Hapus')->requiresConfirmation()])->defaultSort('course_id');
    }
    public static function getPages(): array { return ['index' => Pages\ListCoursePrerequisites::route('/'), 'create' => Pages\CreateCoursePrerequisite::route('/create'), 'edit' => Pages\EditCoursePrerequisite::route('/{record}/edit')]; }
}
''')
print('generated')
