<?php

namespace App\Filament\Resources\Assessments;

use App\Filament\Resources\Assessments\Pages;
use App\Models\Assessment;
use Filament\Forms\Components\{Select, TextInput};
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\{Columns\TextColumn, Table};
use Filament\{Actions\DeleteAction, Actions\EditAction};
use Filament\Schemas\Components\Section;
use App\Filament\Clusters\AssessmentCluster;
use BackedEnum;
use UnitEnum;

class AssessmentResource extends Resource
{
    protected static ?string $slug = 'assessments';
    protected static ?string $cluster = AssessmentCluster::class;
    protected static ?string $model = Assessment::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentCheck;
    protected static ?string $navigationLabel = 'Komponen Penilaian';
    protected static ?string $modelLabel = 'Komponen Penilaian';
    protected static ?string $pluralModelLabel = 'Komponen Penilaian';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informasi Penilaian')
                ->description('Data Komponen Penilain Mahasiswa')
                ->schema([
                    Select::make('course_class_id')
                        ->label('Kelas')->relationship('courseClass', 'class_code')->searchable()->preload()->required(),
                    TextInput::make('name')
                        ->label('Nama Komponen')->required()->maxLength(100),
                    TextInput::make('weight')
                        ->label('Bobot (%)')->numeric()->minValue(0)->maxValue(100)->required(),
                    TextInput::make('max_score')
                        ->label('Nilai Maksimal')->numeric()->minValue(1)->default(100)->required()
                ])->columnSpanFull()->columns(2)
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([TextColumn::make('courseClass.class_code')->label('Kelas'), TextColumn::make('name')->label('Komponen')->searchable(), TextColumn::make('weight')->label('Bobot (%)'), TextColumn::make('max_score')->label('Nilai Maksimal')])->actions([EditAction::make()->label('Ubah'), DeleteAction::make()->label('Hapus')->requiresConfirmation()]);
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ListAssessments::route('/'), 'create' => Pages\CreateAssessment::route('/create'), 'edit' => Pages\EditAssessment::route('/{record}/edit')];
    }
}
