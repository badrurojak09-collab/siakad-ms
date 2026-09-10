<?php

namespace App\Filament\Resources\GradeSubmissions;

use App\Filament\Resources\GradeSubmissions\Pages;
use App\Models\GradeSubmission;
use Filament\Forms\Components\{DateTimePicker, Select};
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\{Columns\TextColumn, Table};
use Filament\{Actions\DeleteAction, Actions\EditAction};
use App\Filament\Clusters\AssessmentCluster;
use Filament\Schemas\Components\Section;

class GradeSubmissionResource extends Resource
{
    protected static ?string $slug = 'grade-submissions';
    protected static ?string $cluster = AssessmentCluster::class;
    protected static ?string $model = GradeSubmission::class;
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedPaperAirplane;
    protected static ?string $navigationLabel = 'Pengajuan Nilai';
    protected static ?string $modelLabel = 'Pengajuan Nilai';
    protected static ?string $pluralModelLabel = 'Pengajuan Nilai';
    protected static ?string $recordTitleAttribute = 'Pengajuan Nilai';
    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informasi Pengajuan Nilai')
                ->description('Data Pengajuan Nilai Mahasiswa')
                ->schema([
                    Select::make('course_class_id')->label('Kelas')->relationship('courseClass', 'class_code')->searchable()->preload()->required(),
                    Select::make('submitted_by')->label('Diajukan Oleh')->relationship('submittedBy', 'name')->searchable()->preload()->required(),
                    Select::make('status')->label('Status')->options(['draft' => 'Draf', 'submitted' => 'Diajukan', 'approved' => 'Disetujui', 'published' => 'Dipublikasikan', 'rejected' => 'Ditolak'])->default('draft')->required(),
                    DateTimePicker::make('submitted_at')->label('Diajukan Pada')->native(false),
                    Select::make('approved_by')->label('Disetujui Oleh')->relationship('approvedBy', 'name')->searchable()->preload()->nullable()
                ])->columnSpanFull()->columns(2)
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([TextColumn::make('courseClass.class_code')->label('Kelas'), TextColumn::make('submittedBy.name')->label('Pengaju'), TextColumn::make('status')->label('Status')->badge(), TextColumn::make('submitted_at')->label('Diajukan Pada')->dateTime('d M Y H:i'), TextColumn::make('approvedBy.name')->label('Penyetuju')->placeholder('-')])->actions([EditAction::make()->label('Ubah')->visible(fn(GradeSubmission $r) => in_array($r->status, ['draft', 'rejected'], true)), DeleteAction::make()->label('Hapus')->visible(fn(GradeSubmission $r) => $r->status === 'draft')->requiresConfirmation()]);
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ListGradeSubmissions::route('/'), 'create' => Pages\CreateGradeSubmission::route('/create'), 'edit' => Pages\EditGradeSubmission::route('/{record}/edit')];
    }
}
