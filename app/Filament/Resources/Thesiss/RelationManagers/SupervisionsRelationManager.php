<?php

namespace App\Filament\Resources\Thesiss\RelationManagers;

use Filament\Actions\{CreateAction, DeleteAction, EditAction};
use Filament\Forms\Components\{DateTimePicker, Select, Textarea};
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SupervisionsRelationManager extends RelationManager
{
    protected static string $relationship = 'supervisions';
    protected static ?string $title = 'Bimbingan';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('supervisor_id')->relationship('supervisor', 'nidn')->searchable()->preload()->required(),
            DateTimePicker::make('meeting_date')->required(),
            Select::make('meeting_type')->options(['online' => 'Online', 'offline' => 'Offline'])->required(),
            Textarea::make('notes')->required()->columnSpanFull(),
            Select::make('status')->options(['scheduled' => 'Scheduled', 'conducted' => 'Conducted', 'cancelled' => 'Dibatalkan'])->default('conducted')->required(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('supervisor.nidn')->label('Pembimbing'), TextColumn::make('meeting_date')->dateTime()->sortable(),
            TextColumn::make('meeting_type')->badge(), TextColumn::make('status')->badge(), TextColumn::make('notes')->limit(60),
        ])->headerActions([CreateAction::make()])->actions([EditAction::make(), DeleteAction::make()]);
    }
}
