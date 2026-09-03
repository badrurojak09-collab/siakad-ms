<?php

namespace App\Filament\Resources\CurriculumTemplates\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CurriculumTemplatesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('curriculum.name')
                    ->label('Kurikulum')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('entry_year')
                    ->label('Angkatan')
                    ->sortable()
                    ->alignCenter(),
                TextColumn::make('total_credits_required')
                    ->label('Target Total SKS')
                    ->sortable()
                    ->alignCenter(),
                TextColumn::make('min_sks_per_semester')
                    ->label('Min SKS/Smt')
                    ->alignCenter(),
                TextColumn::make('max_sks_per_semester')
                    ->label('Max SKS/Smt')
                    ->alignCenter(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('tenant.name')
                    ->searchable(),
            ])
            ->filters([
                SelectFilter::make('curriculum_id')
                    ->label('Kurikulum')
                    ->relationship(
                        name: 'curriculum',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn(Builder $query) => $query->when(
                            filament()->getTenant(),
                            fn($q, $tenant) => $q->where('tenant_id', $tenant->getKey())
                        )
                    ),
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
