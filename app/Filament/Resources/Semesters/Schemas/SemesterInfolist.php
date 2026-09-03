<?php

namespace App\Filament\Resources\Semesters\Schemas;

use App\Models\Semester;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class SemesterInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('tenant.name')
                    ->label('Tenant')
                    ->placeholder('-'),
                TextEntry::make('academic_year_id')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('semester_type'),
                TextEntry::make('start_date')
                    ->date(),
                TextEntry::make('end_date')
                    ->date(),
                TextEntry::make('krs_start_date')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('krs_end_date')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('exam_start_date')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('exam_end_date')
                    ->date()
                    ->placeholder('-'),
                IconEntry::make('is_active')
                    ->boolean(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Semester $record): bool => $record->trashed()),
            ]);
    }
}
