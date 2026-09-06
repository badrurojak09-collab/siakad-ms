<?php

namespace App\Filament\Resources\AcademicBills;

use App\Filament\Resources\AcademicBills\Pages;
use App\Models\AcademicBill;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\{DatePicker, Select, TextInput};
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\{Columns\TextColumn, Table};
use BackedEnum;
use UnitEnum;

class AcademicBillResource extends Resource
{
    protected static ?string $slug = 'academic-bills';
    protected static ?string $model = AcademicBill::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentCurrencyDollar;
    protected static string|UnitEnum|null $navigationGroup = 'Keuangan';
    protected static ?string $navigationLabel = 'Tagihan Akademik';
    protected static ?string $modelLabel = 'Tagihan Akademik';
    protected static ?string $pluralModelLabel = 'Tagihan Akademik';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informasi Tagihan Akademik')
                ->description('Data Tagihan Akademik')
                ->schema([
                    Select::make('student_id')
                        ->label('Mahasiswa')
                        ->relationship('student', 'nim')
                        ->searchable()
                        ->preload()
                        ->required(),
                    Select::make('semester_id')
                        ->label('Semester')
                        ->relationship('semester', 'id')
                        ->searchable()
                        ->preload()
                        ->required(),
                    Select::make('fee_type_id')
                        ->label('Jenis Biaya')
                        ->relationship('feeType', 'name')
                        ->searchable()
                        ->preload()
                        ->required(),
                    TextInput::make('bill_number')
                        ->label('Nomor Tagihan')
                        ->required()
                        ->maxLength(80),
                    DatePicker::make('issued_at')
                        ->label('Tanggal Terbit')
                        ->required(),
                    DatePicker::make('due_date')
                        ->label('Jatuh Tempo')
                        ->afterOrEqual('issued_at')
                        ->required(),
                    TextInput::make('subtotal')
                        ->label('Subtotal')
                        ->numeric()
                        ->minValue(0)
                        ->required(),
                    TextInput::make('discount')
                        ->label('Diskon')
                        ->numeric()
                        ->minValue(0)
                        ->default(0),
                    TextInput::make('penalty')
                        ->label('Denda')
                        ->numeric()
                        ->minValue(0)
                        ->default(0),
                    TextInput::make('total')
                        ->label('Total')
                        ->numeric()
                        ->minValue(0)
                        ->required(),
                    Select::make('status')
                        ->label('Status')
                        ->options([
                            'unpaid' => 'Belum Bayar',
                            'partial' => 'Sebagian',
                            'paid' => 'Lunas',
                            'overdue' => 'Jatuh Tempo'
                        ])
                        ->default('unpaid')
                        ->required()
                ])
                ->columns(2)
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('bill_number')
                    ->label('Nomor')
                    ->searchable(),
                TextColumn::make('student.nim')
                    ->label('NIM'),
                TextColumn::make('student.user.name')
                    ->label('Mahasiswa'),
                TextColumn::make('feeType.name')
                    ->label('Jenis'),
                TextColumn::make('total')
                    ->label('Total')
                    ->money('IDR'),
                TextColumn::make('paid_amount')
                    ->label('Terbayar')
                    ->money('IDR'),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge(),
                TextColumn::make('due_date')
                    ->label('Jatuh Tempo')
                    ->date()
            ])
            ->actions([
                EditAction::make()
                    ->label('Ubah')
                    ->visible(fn(AcademicBill $r) => !in_array($r->status, ['paid'], true)),
                DeleteAction::make()
                    ->label('Hapus')
                    ->visible(fn(AcademicBill $r) => $r->paid_amount == 0)
                    ->requiresConfirmation()
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAcademicBills::route('/'),
            'create' => Pages\CreateAcademicBill::route('/create'),
            'edit' => Pages\EditAcademicBill::route('/{record}/edit')
        ];
    }
}
