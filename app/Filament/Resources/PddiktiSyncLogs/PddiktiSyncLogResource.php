<?php

namespace App\Filament\Resources\PddiktiSyncLogs;

use App\Actions\Pddikti\RetryPddiktiSyncAction;
use App\Filament\Resources\PddiktiSyncLogs\Pages;
use App\Models\PddiktiSyncLog;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Forms\Components\{Select, TextInput, Textarea};
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Support\Icons\Heroicon;
use BackedEnum;
use UnitEnum;

class PddiktiSyncLogResource extends Resource
{
    protected static ?string $model = PddiktiSyncLog::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static string|UnitEnum|null $navigationGroup = 'Pelaporan Akademik';
    protected static ?string $navigationLabel = 'PDDikti Sync Logs';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('entity_type')->required(), TextInput::make('entity_id')->required(), TextInput::make('operation')->required(),
            Select::make('status')->options(['queued' => 'Queued', 'pending' => 'Menunggu', 'processing' => 'Processing', 'synced' => 'Synced', 'failed' => 'Failed', 'retry' => 'Retry'])->required(),
            TextInput::make('retry_count')->numeric()->disabled(), TextInput::make('response_code')->numeric()->disabled(), Textarea::make('response_message')->disabled()->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('id')->sortable(), TextColumn::make('entity_type')->searchable(), TextColumn::make('entity_id')->searchable(),
            TextColumn::make('operation')->badge(), TextColumn::make('status')->badge(), TextColumn::make('retry_count'), TextColumn::make('response_code'), TextColumn::make('last_attempt_at')->dateTime(),
        ])->actions([
            \Filament\Tables\Actions\EditAction::make(),
            Action::make('retry')->label('Retry')->icon(Heroicon::OutlinedArrowPath)->color('warning')->requiresConfirmation()
                ->visible(fn (PddiktiSyncLog $record): bool => in_array($record->status, ['failed', 'retry'], true))
                ->action(function (PddiktiSyncLog $record): void {
                    app(RetryPddiktiSyncAction::class)->execute($record);
                    Notification::make()->title('Sinkronisasi dimasukkan kembali ke queue')->success()->send();
                }),
            \Filament\Tables\Actions\DeleteAction::make(),
        ])->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ListPddiktiSyncLogs::route('/'), 'create' => Pages\CreatePddiktiSyncLog::route('/create'), 'edit' => Pages\EditPddiktiSyncLog::route('/{record}/edit')];
    }
}
