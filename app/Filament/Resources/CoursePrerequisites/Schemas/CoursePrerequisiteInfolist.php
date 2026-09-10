<?php

namespace App\Filament\Resources\CoursePrerequisites\Schemas;

use App\Models\CoursePrerequisite;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;

class CoursePrerequisiteInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Prasyarat Mata Kuliah')
                    ->description('Data Prasyarat Mata Kuliah')
                    ->schema([
                        TextEntry::make('course_id')
                            ->numeric()
                            ->placeholder('-'),
                        TextEntry::make('prerequisite_course_id')
                            ->numeric()
                            ->placeholder('-'),
                        IconEntry::make('is_mandatory')
                            ->boolean(),
                        TextEntry::make('created_at')
                            ->dateTime()
                            ->placeholder('-'),
                        TextEntry::make('updated_at')
                            ->dateTime()
                            ->placeholder('-'),
                        TextEntry::make('deleted_at')
                            ->dateTime()
                            ->visible(fn(CoursePrerequisite $record): bool => $record->trashed()),
                        TextEntry::make('tenant.name')
                            ->label('Tenant')
                            ->placeholder('-')
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }
}
