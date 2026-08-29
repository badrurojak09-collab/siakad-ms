<?php
namespace App\Filament\Resources\CeremonyRegistrations;
use App\Models\CeremonyRegistration;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Forms\Components\{TextInput,Textarea};
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Support\Icons\Heroicon;
use BackedEnum;
use UnitEnum;
class CeremonyRegistrationResource extends Resource { protected static ?string $model=CeremonyRegistration::class; protected static string|BackedEnum|null $navigationIcon=Heroicon::OutlinedRectangleStack; protected static string|UnitEnum|null $navigationGroup = 'Kelulusan';
    protected static ?string $navigationLabel='Kelulusan'; public static function form(Schema $schema):Schema{return $schema->components([TextInput::make('payment_status')->label('Status Pembayaran')->maxLength(255),TextInput::make('confirmation_status')->label('Status Konfirmasi')->maxLength(255),TextInput::make('gown_size')->label('Ukuran Toga')->maxLength(255),Textarea::make('description')->columnSpanFull(),]);} public static function table(Table $table):Table{return $table->columns([TextColumn::make('id')->sortable(),TextColumn::make('code')->searchable(),TextColumn::make('name')->searchable(),TextColumn::make('status')->badge(),TextColumn::make('created_at')->dateTime()->sortable()])->actions([\Filament\Tables\Actions\EditAction::make(),\Filament\Tables\Actions\DeleteAction::make()])->defaultSort('created_at','desc');} public static function getPages():array{return ['index'=>Pages\ListCeremonyRegistrations::route('/'),'create'=>Pages\CreateCeremonyRegistration::route('/create'),'edit'=>Pages\EditCeremonyRegistration::route('/{record}/edit')];} }
