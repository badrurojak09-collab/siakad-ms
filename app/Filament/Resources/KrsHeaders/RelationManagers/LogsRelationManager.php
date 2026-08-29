<?php

namespace App\Filament\Resources\KrsHeaders\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LogsRelationManager extends RelationManager
{
    protected static string $relationship = 'logs';
    protected static ?string $title = 'Riwayat KRS';
    public function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('from_status')->label('Dari'),
            TextColumn::make('to_status')->label('Ke'),
            TextColumn::make('changed_at')->dateTime(),
            TextColumn::make('changed_by')->label('Actor'),
            TextColumn::make('reason')->wrap(),
        ])->paginated([10, 25, 50]);
    }
}
