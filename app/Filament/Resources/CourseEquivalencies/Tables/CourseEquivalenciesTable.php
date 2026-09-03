<?php

namespace App\Filament\Resources\CourseEquivalencies\Tables;

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

class CourseEquivalenciesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('student.user.name')
                    ->label('Mahasiswa')
                    ->description(fn($record) => "NIM: {$record->student?->nim}")
                    ->searchable()
                    ->sortable(),
                TextColumn::make('originalCourse.name')
                    ->label('Matkul Asal')
                    ->description(fn($record) => "Kode: {$record->originalCourse?->code}")
                    ->searchable(),
                TextColumn::make('equivalentCourse.name')
                    ->label('Matkul Diakui')
                    ->description(fn($record) => "Kode: {$record->equivalentCourse?->code}")
                    ->searchable(),
                TextColumn::make('approver.name')
                    ->label('Disetujui Oleh')
                    ->placeholder('Belum Disetujui')
                    ->toggleable(),
                TextColumn::make('approved_at')
                    ->label('Tgl Persetujuan')
                    ->dateTime('d M Y H:i')
                    ->placeholder('-')
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('student_id')
                    ->label('Mahasiswa')
                    ->options(function () {
                        return \App\Models\Student::with('user')
                            ->when(
                                filament()->getTenant(),
                                fn($q, $tenant) => $q->where('tenant_id', $tenant->getKey())
                            )
                            ->get()
                            ->pluck('user.name', 'id')
                            ->toArray();
                    })
                    ->searchable(),
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
