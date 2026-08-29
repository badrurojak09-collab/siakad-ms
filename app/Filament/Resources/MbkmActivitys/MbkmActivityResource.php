<?php

namespace App\Filament\Resources\MbkmActivitys;

use App\Filament\Resources\Concerns\ScopesOwnStudentRecords;

use App\Actions\Administration\RegisterMbkmActivityAction;
use App\Models\MbkmActivity;
use Filament\Forms\Components\{DatePicker, Select, TextInput};
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use BackedEnum;
use UnitEnum;

class MbkmActivityResource extends Resource
{
    use ScopesOwnStudentRecords;
    protected static ?string $model = MbkmActivity::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static string|UnitEnum|null $navigationGroup = 'Administrasi Mahasiswa';
    protected static ?string $navigationLabel = 'Aktivitas MBKM';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('student_id')->relationship('student', 'nim')->searchable()->preload()->required(),
            Select::make('activity_type')->options(['internship' => 'Magang', 'exchange' => 'Pertukaran', 'research' => 'Riset', 'community' => 'Pengabdian', 'entrepreneurship' => 'Kewirausahaan'])->required(),
            TextInput::make('institution_name')->required()->maxLength(255), DatePicker::make('start_date')->required(), DatePicker::make('end_date')->required()->afterOrEqual('start_date'),
            TextInput::make('credits_recognized')->numeric()->minValue(0)->maxValue(60),
            Select::make('recognition_course_id')->relationship('recognitionCourse', 'name')->searchable()->preload()->nullable(),
            Select::make('supervisor_id')->relationship('supervisor', 'nidn')->searchable()->preload()->nullable(),
            Select::make('status')->options(['pending' => 'Menunggu', 'approved' => 'Disetujui', 'rejected' => 'Ditolak', 'completed' => 'Selesai'])->disabled(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('student.nim')->label('NIM')->searchable(), TextColumn::make('activity_type')->badge(), TextColumn::make('institution_name')->searchable(), TextColumn::make('start_date')->date(), TextColumn::make('end_date')->date(), TextColumn::make('credits_recognized')->label('SKS'), TextColumn::make('status')->badge(),
        ])->actions([
            \Filament\Tables\Actions\EditAction::make()->visible(fn (MbkmActivity $record): bool => in_array($record->status, ['pending', 'rejected'], true)),
            \Filament\Tables\Actions\DeleteAction::make()->visible(fn (MbkmActivity $record): bool => $record->status === 'pending'),
        ])->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array { return ['index' => Pages\ListMbkmActivitys::route('/'), 'create' => Pages\CreateMbkmActivity::route('/create'), 'edit' => Pages\EditMbkmActivity::route('/{record}/edit')]; }
}
