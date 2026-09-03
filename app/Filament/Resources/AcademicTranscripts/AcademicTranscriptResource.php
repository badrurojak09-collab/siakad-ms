<?php

namespace App\Filament\Resources\AcademicTranscripts;

use App\Actions\Grading\{FinalizeTranscriptAction, SignAcademicTranscriptAction};
use App\Actions\Reporting\{ExportAcademicTranscriptExcelAction, ExportAcademicTranscriptPdfAction};
use App\Filament\Resources\AcademicTranscripts\RelationManagers\ItemsRelationManager;
use App\Filament\Resources\AcademicTranscripts\Pages;
use App\Filament\Resources\Concerns\ScopesOwnStudentRecords;
use App\Models\AcademicTranscript;
use Filament\Actions\{EditAction, DeleteAction};
use Filament\Actions\Action;
use Filament\Forms\Components\{Select, TextInput};
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
// use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use BackedEnum;
use UnitEnum;

class AcademicTranscriptResource extends Resource
{
    use ScopesOwnStudentRecords;

    protected static ?string $model = AcademicTranscript::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;
    protected static string|UnitEnum|null $navigationGroup = 'Pelaporan Akademik';
    protected static ?string $navigationLabel = 'KHS & Transkrip';
    protected static ?string $modelLabel = 'Transkrip Akademik';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('student_id')->relationship('student', 'nim')->searchable()->preload()->required(),
            Select::make('type')->options(['khs' => 'KHS', 'transcript' => 'Transkrip'])->required()->default('khs'),
            Select::make('semester_id')->relationship('semester', 'id')->searchable()->preload()->nullable(),
            TextInput::make('total_credits')->numeric()->disabled(),
            TextInput::make('total_quality_points')->numeric()->disabled(),
            TextInput::make('gpa')->numeric()->disabled(),
            Select::make('status')->options(['draft' => 'Draf', 'generated' => 'Generated', 'final' => 'Final'])->disabled(),
            TextInput::make('signature_hash')->disabled()->dehydrated(false),
            TextInput::make('signer_name')->disabled()->dehydrated(false),
            TextInput::make('signer_title')->disabled()->dehydrated(false),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('id')->sortable(),
            TextColumn::make('student_id')->label('Mahasiswa')->sortable(),
            TextColumn::make('type')->badge(),
            TextColumn::make('semester_id')->label('Semester'),
            TextColumn::make('gpa')->label('IPK/IPS')->sortable(),
            TextColumn::make('status')->badge(),
            TextColumn::make('signature_hash')->label('Tanda Tangan')->formatStateUsing(fn(?string $state): string => $state ? 'Tersedia' : 'Belum')->badge(),
            TextColumn::make('generated_at')->dateTime()->sortable(),
        ])->actions([
            EditAction::make()->visible(fn(AcademicTranscript $record): bool => $record->status !== 'final'),
            Action::make('finalize')
                ->label('Finalisasi')
                ->icon(Heroicon::OutlinedLockClosed)
                ->color('warning')
                ->requiresConfirmation()
                ->visible(fn(AcademicTranscript $record): bool => $record->status === 'generated')
                ->action(function (AcademicTranscript $record): void {
                    app(FinalizeTranscriptAction::class)->execute($record, (int) (Auth::id() ?: 1));
                    Notification::make()->title('Transkrip berhasil difinalisasi')->success()->send();
                }),
            Action::make('sign')
                ->label('Tanda Tangani')
                ->icon(Heroicon::OutlinedPencilSquare)
                ->color('success')
                ->form([TextInput::make('signer_name')->label('Nama Penandatangan')->required(), TextInput::make('signer_title')->label('Jabatan')->required()])
                ->visible(fn(AcademicTranscript $record): bool => $record->status === 'final' && blank($record->signature_hash))
                ->action(function (AcademicTranscript $record, array $data): void {
                    app(SignAcademicTranscriptAction::class)->execute($record, (int) (Auth::id() ?: 1), $data['signer_name'], $data['signer_title']);
                    Notification::make()->title('Transkrip berhasil ditandatangani')->success()->send();
                }),
            Action::make('verify_signature')
                ->label('Verifikasi')
                ->icon(Heroicon::OutlinedShieldCheck)
                ->color('info')
                ->visible(fn(AcademicTranscript $record): bool => filled($record->signature_hash))
                ->action(function (AcademicTranscript $record): void {
                    $valid = app(SignAcademicTranscriptAction::class)->verify($record);
                    $notification = Notification::make()->title($valid ? 'Tanda tangan valid' : 'Tanda tangan tidak valid');
                    ($valid ? $notification->success() : $notification->danger())->send();
                }),
            Action::make('export_pdf')
                ->label('PDF')
                ->icon(Heroicon::OutlinedDocumentArrowDown)
                ->visible(fn(AcademicTranscript $record): bool => $record->status === 'final')
                ->action(fn(AcademicTranscript $record) => app(ExportAcademicTranscriptPdfAction::class)->execute($record, (int) (Auth::id() ?: 1))),
            Action::make('export_excel')
                ->label('Excel')
                ->icon(Heroicon::OutlinedTableCells)
                ->visible(fn(AcademicTranscript $record): bool => $record->status === 'final')
                ->action(fn(AcademicTranscript $record) => app(ExportAcademicTranscriptExcelAction::class)->execute($record, (int) (Auth::id() ?: 1))),
            DeleteAction::make(),
        ])->defaultSort('generated_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [ItemsRelationManager::class];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAcademicTranscripts::route('/'),
            'create' => Pages\CreateAcademicTranscript::route('/create'),
            'edit' => Pages\EditAcademicTranscript::route('/{record}/edit'),
        ];
    }
}
