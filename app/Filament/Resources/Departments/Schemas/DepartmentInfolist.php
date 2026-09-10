<?php

namespace App\Filament\Resources\Departments\Schemas;

use App\Models\Department;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;

class DepartmentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Departmen Perguruan Tinggi')
                    ->description('Data Departmen Perguruan Tinggi')
                    ->schema([
                        TextEntry::make('tenant.name')
                            ->label('Tenant')
                            ->placeholder('-'),
                        TextEntry::make('faculty.name')
                            ->label('Faculty')
                            ->placeholder('-'),
                        TextEntry::make('code'),
                        TextEntry::make('name'),
                        TextEntry::make('head_of_dept_id')
                            ->numeric()
                            ->placeholder('-'),
                        TextEntry::make('created_at')
                            ->dateTime()
                            ->placeholder('-'),
                        TextEntry::make('updated_at')
                            ->dateTime()
                            ->placeholder('-'),
                        TextEntry::make('deleted_at')
                            ->dateTime()
                            ->visible(fn(Department $record): bool => $record->trashed())
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }
}
