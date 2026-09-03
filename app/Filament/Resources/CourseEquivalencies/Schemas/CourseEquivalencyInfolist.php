<?php

namespace App\Filament\Resources\CourseEquivalencies\Schemas;

use App\Models\CourseEquivalency;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class CourseEquivalencyInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('tenant.name')
                    ->label('Tenant')
                    ->placeholder('-'),
                TextEntry::make('student.id')
                    ->label('Student')
                    ->placeholder('-'),
                TextEntry::make('originalCourse.name')
                    ->label('Original course')
                    ->placeholder('-'),
                TextEntry::make('equivalentCourse.name')
                    ->label('Equivalent course')
                    ->placeholder('-'),
                TextEntry::make('reason')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('approved_by')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('approved_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (CourseEquivalency $record): bool => $record->trashed()),
            ]);
    }
}
