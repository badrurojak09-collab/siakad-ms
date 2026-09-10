<?php

namespace App\Filament\Resources\Curricula\Schemas;

use App\Models\Curriculum;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;

class CurriculumInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Kurikulum')
                    ->description('Data Kurikulum')
                    ->schema([
                        TextEntry::make('tenant.name')
                            ->label('Tenant')
                            ->placeholder('-'),
                        TextEntry::make('studyProgram.name')
                            ->label('Study program')
                            ->placeholder('-'),
                        TextEntry::make('name'),
                        TextEntry::make('year')
                            ->numeric(),
                        TextEntry::make('description')
                            ->placeholder('-')
                            ->columnSpanFull(),
                        TextEntry::make('status'),
                        TextEntry::make('created_at')
                            ->dateTime()
                            ->placeholder('-'),
                        TextEntry::make('updated_at')
                            ->dateTime()
                            ->placeholder('-'),
                        TextEntry::make('deleted_at')
                            ->dateTime()
                            ->visible(fn(Curriculum $record): bool => $record->trashed())
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }
}
