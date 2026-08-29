<?php

namespace App\Filament\Resources\KrsHeaders;

use App\Filament\Resources\Concerns\ScopesOwnStudentRecords;

use App\Filament\Resources\KrsHeaders\RelationManagers\{DetailsRelationManager, LogsRelationManager};
use App\Models\KrsHeader;
use Filament\Forms\Components\{Select, TextInput};
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use BackedEnum;
use UnitEnum;

class KrsHeaderResource extends Resource
{
    use ScopesOwnStudentRecords;
    protected static ?string $model = KrsHeader::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static string|UnitEnum|null $navigationGroup = 'KRS & Registrasi';
    protected static ?string $navigationLabel = 'Kartu Rencana Studi';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('student_id')->relationship('student', 'nim')->searchable()->preload()->required(),
            Select::make('semester_id')->relationship('semester', 'id')->searchable()->preload()->required(),
            TextInput::make('total_credits')->numeric()->minValue(0)->disabled(),
            Select::make('status')->options(['draft' => 'Draf', 'submitted' => 'Diajukan', 'approved' => 'Disetujui', 'revision_required' => 'Revision Required', 'rejected' => 'Ditolak'])->disabled(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('id')->sortable(), TextColumn::make('student.nim')->label('NIM')->searchable(), TextColumn::make('semester.id')->label('Semester'),
            TextColumn::make('total_credits')->label('SKS'), TextColumn::make('status')->badge(), TextColumn::make('submitted_at')->dateTime()->sortable(),
        ])->actions([
            \Filament\Tables\Actions\EditAction::make()->visible(fn (KrsHeader $record): bool => in_array($record->status, ['draft', 'revision_required'], true)),
            \Filament\Tables\Actions\DeleteAction::make()->visible(fn (KrsHeader $record): bool => $record->status === 'draft'),
        ])->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [DetailsRelationManager::class, LogsRelationManager::class];
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ListKrsHeaders::route('/'), 'create' => Pages\CreateKrsHeader::route('/create'), 'edit' => Pages\EditKrsHeader::route('/{record}/edit')];
    }
}
