<?php

namespace App\Filament\Resources\LeaveRequests;

use App\Actions\Administration\ApproveLeaveRequestAction;
use App\Filament\Resources\Concerns\ScopesOwnStudentRecords;
use App\Models\LeaveRequest;
use Filament\Actions\Action;
use Filament\Forms\Components\{DatePicker, Select, Textarea, TextInput};
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

class LeaveRequestResource extends Resource
{
    use ScopesOwnStudentRecords;

    protected static ?string $model = LeaveRequest::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static string|UnitEnum|null $navigationGroup = 'Administrasi Mahasiswa';
    protected static ?string $navigationLabel = 'Pengajuan Cuti';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('student_id')->relationship('student', 'nim')->searchable()->preload()->required(),
            Select::make('semester_id')->relationship('semester', 'id')->searchable()->preload()->required(),
            Select::make('start_semester_id')->relationship('startSemester', 'id')->searchable()->preload()->required(),
            Select::make('end_semester_id')->relationship('endSemester', 'id')->searchable()->preload()->required(),
            Textarea::make('reason')->required()->columnSpanFull(),
            Select::make('status')->options(['pending' => 'Menunggu', 'approved' => 'Disetujui', 'rejected' => 'Ditolak'])->disabled(),
            TextInput::make('approved_by')->disabled()->dehydrated(false),
            DatePicker::make('approved_at')->disabled()->dehydrated(false),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('student.nim')->label('NIM')->searchable(),
            TextColumn::make('semester.id')->label('Semester'),
            TextColumn::make('reason')->limit(60),
            TextColumn::make('status')->badge(),
            TextColumn::make('approved_at')->dateTime(),
        ])->actions([
            EditAction::make()->visible(fn(LeaveRequest $record): bool => $record->status === 'pending'),
            Action::make('approve')
                ->label('Proses')
                ->icon(Heroicon::OutlinedCheckCircle)
                ->requiresConfirmation()
                ->visible(fn(LeaveRequest $record): bool => $record->status === 'pending')
                ->form([Select::make('decision')->options(['approved' => 'Setujui', 'rejected' => 'Tolak'])->required(), Textarea::make('note')])
                ->action(function (LeaveRequest $record, array $data): void {
                    app(ApproveLeaveRequestAction::class)->execute($record, (int) (Auth::id() ?: 1), $data['decision'] === 'approved', $data['note'] ?? null);
                    Notification::make()->title('Pengajuan cuti diproses')->success()->send();
                }),
            DeleteAction::make()->visible(fn(LeaveRequest $record): bool => $record->status === 'pending'),
        ])->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ListLeaveRequests::route('/'), 'create' => Pages\CreateLeaveRequest::route('/create'), 'edit' => Pages\EditLeaveRequest::route('/{record}/edit')];
    }
}
