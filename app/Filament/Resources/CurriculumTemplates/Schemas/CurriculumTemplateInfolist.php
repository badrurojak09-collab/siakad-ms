<?php

namespace App\Filament\Resources\CurriculumTemplates\Schemas;

use App\Models\CurriculumTemplate;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class CurriculumTemplateInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('curriculum.name')
                    ->label('Curriculum')
                    ->placeholder('-'),
                TextEntry::make('entry_year')
                    ->numeric(),
                TextEntry::make('max_sks_per_semester')
                    ->numeric(),
                TextEntry::make('min_sks_per_semester')
                    ->numeric(),
                TextEntry::make('total_credits_required')
                    ->numeric(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (CurriculumTemplate $record): bool => $record->trashed()),
                TextEntry::make('tenant.name')
                    ->label('Tenant')
                    ->placeholder('-'),
            ]);
    }
}
