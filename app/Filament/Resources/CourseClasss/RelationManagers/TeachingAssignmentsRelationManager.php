<?php

namespace App\Filament\Resources\CourseClasss\RelationManagers;

use App\Actions\Teaching\AssignLecturerAction;
use App\Models\TeachingAssignment;
use Filament\Forms\Components\{Select, TextInput};
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\{Actions\Action, Actions\DeleteAction, Actions\EditAction, Columns\TextColumn, Table};

class TeachingAssignmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'teachingAssignments';
    protected static ?string $title = 'Dosen Pengampu';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('lecturer_id')->label('Dosen')->relationship('lecturer', 'nidn')->searchable()->preload()->required(),
            Select::make('role')->label('Peran')->options(['primary'=>'Dosen Utama', 'co'=>'Co-Dosen'])->default('primary')->required(),
            TextInput::make('teaching_load')->label('Beban Mengajar')->numeric()->minValue(0)->default(1),
            Select::make('status')->label('Status')->options(['active'=>'Aktif', 'inactive'=>'Tidak Aktif'])->default('active')->required(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('lecturer.user.name')->label('Dosen')->searchable(),
            TextColumn::make('role')->label('Peran')->badge(),
            TextColumn::make('teaching_load')->label('Beban Mengajar'),
            TextColumn::make('status')->label('Status')->badge(),
        ])->headerActions([
            Action::make('tambah')->label('Tambah Dosen Pengampu')->form([
                Select::make('lecturer_id')->label('Dosen')->relationship('lecturer', 'nidn')->searchable()->preload()->required(),
                Select::make('role')->label('Peran')->options(['primary'=>'Dosen Utama', 'co'=>'Co-Dosen'])->default('primary')->required(),
                TextInput::make('teaching_load')->label('Beban Mengajar')->numeric()->minValue(0)->default(1),
            ])->action(function (array $data): void {
                app(AssignLecturerAction::class)->execute($this->getOwnerRecord(), \App\Models\Lecturer::findOrFail($data['lecturer_id']), $data['role']);
            }),
        ])->actions([
            EditAction::make()->label('Ubah'), DeleteAction::make()->label('Hapus')->requiresConfirmation(),
        ]);
    }
}
