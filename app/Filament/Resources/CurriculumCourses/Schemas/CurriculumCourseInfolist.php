<?php

namespace App\Filament\Resources\CurriculumCourses\Schemas;

use App\Models\CurriculumCourse;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class CurriculumCourseInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('curriculum.name')
                    ->label('Curriculum')
                    ->placeholder('-'),
                TextEntry::make('course.name')
                    ->label('Course')
                    ->placeholder('-'),
                TextEntry::make('semester')
                    ->numeric(),
                IconEntry::make('is_mandatory')
                    ->boolean(),
                TextEntry::make('concentration')
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (CurriculumCourse $record): bool => $record->trashed()),
                TextEntry::make('tenant.name')
                    ->label('Tenant')
                    ->placeholder('-'),
            ]);
    }
}
