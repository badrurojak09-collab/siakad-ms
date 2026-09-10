<?php

namespace App\Filament\Resources\Courses\Schemas;

use App\Models\Course;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;

class CourseInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Mata Kuliah')
                    ->description('Data identitas dan bobot SKS mata kuliah.')
                    ->schema([
                        TextEntry::make('tenant.name')
                            ->label('Tenant')
                            ->placeholder('-'),
                        TextEntry::make('code'),
                        TextEntry::make('name'),
                        TextEntry::make('credits')
                            ->numeric(),
                        TextEntry::make('theory_credits')
                            ->numeric(),
                        TextEntry::make('practice_credits')
                            ->numeric(),
                        TextEntry::make('description')
                            ->placeholder('-')
                            ->columnSpanFull(),
                        TextEntry::make('course_type')
                            ->placeholder('-'),
                        TextEntry::make('created_at')
                            ->dateTime()
                            ->placeholder('-'),
                        TextEntry::make('updated_at')
                            ->dateTime()
                            ->placeholder('-'),
                        TextEntry::make('deleted_at')
                            ->dateTime()
                            ->visible(fn(Course $record): bool => $record->trashed())
                    ])
                    ->collapsible()
                    ->collapsed()
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }
}
