<?php

namespace App\Filament\Resources\CurriculumCourses\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CurriculumCoursesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('curriculum.name')
                    ->label('Kurikulum')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('course.code')
                    ->label('Kode Matkul')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('course.name')
                    ->label('Nama Mata Kuliah')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('semester')
                    ->label('Semester')
                    ->sortable()
                    ->alignCenter(),
                IconColumn::make('is_mandatory')
                    ->label('Wajib')
                    ->boolean()
                    ->alignCenter(),
                TextColumn::make('concentration')
                    ->label('Konsentrasi')
                    ->placeholder('-')
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('curriculum_id')
                    ->label('Kurikulum')
                    ->relationship(
                        name: 'curriculum',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn(Builder $query) => $query->where('tenant_id', filament()->getTenant()?->getKey())
                    ),
                SelectFilter::make('semester')
                    ->options([
                        1 => 'Semester 1',
                        2 => 'Semester 2',
                        3 => 'Semester 3',
                        4 => 'Semester 4',
                        5 => 'Semester 5',
                        6 => 'Semester 6',
                        7 => 'Semester 7',
                        8 => 'Semester 8',
                    ]),
                TernaryFilter::make('is_mandatory')
                    ->label('Status Wajib'),
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
