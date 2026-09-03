<?php

namespace App\Filament\Resources\CoursePrerequisites\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\{SelectFilter, TernaryFilter, TrashedFilter};
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CoursePrerequisitesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('course.code')
                    ->label('Kode Matkul')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('course.name')
                    ->label('Mata Kuliah Utama')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('prerequisiteCourse.code')
                    ->label('Kode Syarat')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('prerequisiteCourse.name')
                    ->label('Mata Kuliah Prasyarat')
                    ->searchable()
                    ->sortable(),
                IconColumn::make('is_mandatory')
                    ->label('Wajib Lulus')
                    ->boolean()
                    ->alignCenter(),
            ])
            ->filters([
                SelectFilter::make('course_id')
                    ->label('Mata Kuliah Utama')
                    ->relationship(
                        name: 'course',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn(Builder $query) => $query->when(
                            filament()->getTenant(),
                            fn($q, $tenant) => $q->where('tenant_id', $tenant->getKey())
                        )
                    ),
                TernaryFilter::make('is_mandatory')
                    ->label('Status Wajib Lulus'),
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
