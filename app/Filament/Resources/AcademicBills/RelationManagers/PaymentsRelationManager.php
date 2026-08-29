<?php

namespace App\Filament\Resources\AcademicBills\RelationManagers;

use Filament\Actions\{CreateAction, DeleteAction, EditAction};
use Filament\Forms\Components\{DateTimePicker, Select, TextInput};
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PaymentsRelationManager extends RelationManager
{
    protected static string $relationship = 'payments';
    protected static ?string $title = 'Pembayaran';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('payment_number')->required()->maxLength(80), TextInput::make('amount')->numeric()->minValue(1)->required(),
            Select::make('method')->options(['cash' => 'Tunai', 'transfer' => 'Transfer', 'virtual_account' => 'Virtual Account', 'gateway' => 'Payment Gateway'])->required(),
            TextInput::make('reference')->maxLength(150), DateTimePicker::make('paid_at'),
            Select::make('status')->options(['pending' => 'Menunggu', 'confirmed' => 'Terkonfirmasi', 'failed' => 'Gagal', 'refunded' => 'Refund'])->required(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('payment_number')->searchable(), TextColumn::make('amount')->money('IDR'), TextColumn::make('method')->badge(),
            TextColumn::make('status')->badge(), TextColumn::make('paid_at')->dateTime(),
        ])->headerActions([CreateAction::make()])->actions([
            EditAction::make()->visible(fn ($record): bool => !in_array($record->status, ['confirmed', 'refunded'], true)),
            DeleteAction::make()->visible(fn ($record): bool => $record->status === 'failed'),
        ]);
    }
}
