<?php
namespace App\Filament\Resources\Departments;

use App\Filament\Resources\Departments\Pages;
use App\Models\Department;
use Filament\Forms\Components\{Select, TextInput};
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\{Columns\TextColumn, Table};
use Filament\{Actions\DeleteAction, Actions\EditAction};
use BackedEnum;
use UnitEnum;

class DepartmentResource extends Resource
{
    protected static ?string $slug = 'departments';
    protected static ?string $model = Department::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice2;
    protected static string|UnitEnum|null $navigationGroup = 'Organisasi Akademik';
    protected static ?string $navigationLabel = 'Departemen';
    protected static ?string $modelLabel = 'Departemen';
    protected static ?string $pluralModelLabel = 'Departemen';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([Select::make('faculty_id')->label('Fakultas')->relationship('faculty', 'name')->searchable()->preload()->required(), TextInput::make('code')->label('Kode Departemen')->required()->unique(ignoreRecord: true)->maxLength(30), TextInput::make('name')->label('Nama Departemen')->required()->maxLength(255), Select::make('head_of_dept_id')->label('Ketua Departemen')->relationship('headOfDepartment', 'nidn')->searchable()->preload()->nullable()]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([TextColumn::make('faculty.name')->label('Fakultas')->searchable(), TextColumn::make('code')->label('Kode')->searchable()->sortable(), TextColumn::make('name')->label('Nama Departemen')->searchable()->sortable(), TextColumn::make('headOfDepartment.user.name')->label('Ketua Departemen')->placeholder('Belum ditetapkan'), TextColumn::make('study_programs_count')->label('Jumlah Program Studi')->counts('studyPrograms')])->actions([EditAction::make()->label('Ubah'), DeleteAction::make()->label('Hapus')->requiresConfirmation()])->defaultSort('code');
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ListDepartments::route('/'), 'create' => Pages\CreateDepartment::route('/create'), 'edit' => Pages\EditDepartment::route('/{record}/edit')];
    }
}
