<?php
namespace App\Filament\Resources\Supervisions;
use App\Models\Supervision;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Forms\Components\{TextInput,Textarea};
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Support\Icons\Heroicon;
use BackedEnum;
use UnitEnum;
class SupervisionResource extends Resource { protected static ?string $model=Supervision::class; protected static string|BackedEnum|null $navigationIcon=Heroicon::OutlinedRectangleStack; protected static string|UnitEnum|null $navigationGroup = 'Tugas Akhir';
    protected static ?string $navigationLabel='Tugas Akhir'; public static function form(Schema $schema):Schema{return $schema->components([TextInput::make('meeting_type')->label('Meeting Type')->maxLength(255),TextInput::make('status')->label('Status')->maxLength(255),Textarea::make('description')->columnSpanFull(),]);} public static function table(Table $table):Table{return $table->columns([TextColumn::make('id')->sortable(),TextColumn::make('code')->searchable(),TextColumn::make('name')->searchable(),TextColumn::make('status')->badge(),TextColumn::make('created_at')->dateTime()->sortable()])->actions([\Filament\Tables\Actions\EditAction::make(),\Filament\Tables\Actions\DeleteAction::make()])->defaultSort('created_at','desc');} public static function getPages():array{return ['index'=>Pages\ListSupervisions::route('/'),'create'=>Pages\CreateSupervision::route('/create'),'edit'=>Pages\EditSupervision::route('/{record}/edit')];} }
