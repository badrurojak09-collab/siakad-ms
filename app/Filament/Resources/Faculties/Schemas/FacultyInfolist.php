<?php

namespace App\Filament\Resources\Faculties\Schemas;

use App\Models\Faculty;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class FacultyInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('tenant.name')
                    ->label('Tenant')
                    ->placeholder('-'),
                TextEntry::make('code'),
                TextEntry::make('name'),
                TextEntry::make('dean.id')
                    ->label('Dean')
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Faculty $record): bool => $record->trashed()),
            ]);
    }
}
