<?php

namespace App\Filament\Resources\Students\Tables;

use Filament\Actions\{EditAction, DeleteAction, ViewAction};
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class StudentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn(Builder $query) => $query->with('tenant'))
            ->columns([
                TextColumn::make('nim')
                    ->label('NIM')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Nama Mahasiswa')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('studyProgram.name')
                    ->label('Program Studi')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('entry_year')
                    ->label('Tahun Masuk')
                    ->sortable(),
                TextColumn::make('admission_type')
                    ->label('Jalur Masuk')
                    ->badge(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge(),
            ])
            ->filters([
                SelectFilter::make('status')->label('Status')->options(['active' => 'Aktif', 'inactive' => 'Tidak Aktif', 'graduated' => 'Lulus', 'dropped' => 'Mengundurkan Diri', 'leave' => 'Cuti']),
                TrashedFilter::make(),
            ])
            ->defaultSort('nim')
            ->actions([
                EditAction::make()->label('Ubah'),
                DeleteAction::make()->label('Hapus')->requiresConfirmation(),
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
