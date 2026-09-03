<?php

namespace App\Filament\Resources\Transfers;

use App\Actions\Administration\ProcessTransferAction;
use App\Filament\Resources\Concerns\ScopesOwnStudentRecords;
use App\Models\Transfer;
use Filament\Actions\Action;
use Filament\Forms\Components\{DatePicker, Select};
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\{Actions\DeleteAction, Actions\EditAction};
use Illuminate\Support\Facades\Auth;
use BackedEnum;
use UnitEnum;

class TransferResource extends Resource
{
    use ScopesOwnStudentRecords;

    protected static ?string $model = Transfer::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArrowsRightLeft;
    protected static string|UnitEnum|null $navigationGroup = 'Administrasi Mahasiswa';
    protected static ?string $navigationLabel = 'Mutasi Mahasiswa';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('student_id')->relationship('student', 'nim')->searchable()->preload()->required(),
            Select::make('from_study_program_id')->relationship('fromStudyProgram', 'name')->searchable()->preload()->required(),
            Select::make('to_study_program_id')->relationship('toStudyProgram', 'name')->searchable()->preload()->different('from_study_program_id')->required(),
            DatePicker::make('request_date')->required(),
            Select::make('status')->options(['pending' => 'Menunggu', 'approved' => 'Disetujui', 'rejected' => 'Ditolak'])->disabled(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('student.nim')->label('NIM')->searchable(),
            TextColumn::make('fromStudyProgram.name')->label('Asal'),
            TextColumn::make('toStudyProgram.name')->label('Tujuan'),
            TextColumn::make('request_date')->date(),
            TextColumn::make('status')->badge(),
            TextColumn::make('approved_at')->dateTime(),
        ])->actions([
            EditAction::make()->visible(fn(Transfer $record): bool => $record->status === 'pending'),
            Action::make('process')
                ->label('Proses')
                ->icon(Heroicon::OutlinedCheckCircle)
                ->requiresConfirmation()
                ->visible(fn(Transfer $record): bool => $record->status === 'pending')
                ->form([Select::make('decision')->options(['approved' => 'Setujui', 'rejected' => 'Tolak'])->required()])
                ->action(function (Transfer $record, array $data): void {
                    app(ProcessTransferAction::class)->execute($record, (int) (Auth::id() ?: 1), $data['decision'] === 'approved');
                    Notification::make()->title('Mutasi diproses')->success()->send();
                }),
            DeleteAction::make()->visible(fn(Transfer $record): bool => $record->status === 'pending'),
        ])->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ListTransfers::route('/'), 'create' => Pages\CreateTransfer::route('/create'), 'edit' => Pages\EditTransfer::route('/{record}/edit')];
    }
}
