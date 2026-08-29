<?php
namespace App\Filament\Resources\Semesters;
use App\Models\Semester;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Forms\Components\{TextInput,Textarea};
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Support\Icons\Heroicon;
use BackedEnum;
use UnitEnum;
class SemesterResource extends Resource { protected static ?string $model=Semester::class; protected static string|BackedEnum|null $navigationIcon=Heroicon::OutlinedRectangleStack; protected static string|UnitEnum|null $navigationGroup = 'Data Akademik';
    protected static ?string $navigationLabel='Periode Akademik'; public static function form(Schema $schema):Schema{return $schema->components([TextInput::make('semester_type')->label('Semester Type')->maxLength(255),Textarea::make('description')->columnSpanFull(),]);} public static function table(Table $table):Table{return $table->columns([TextColumn::make('id')->sortable(),TextColumn::make('code')->searchable(),TextColumn::make('name')->searchable(),TextColumn::make('status')->badge(),TextColumn::make('created_at')->dateTime()->sortable()])->actions([\Filament\Tables\Actions\EditAction::make(),\Filament\Tables\Actions\DeleteAction::make()])->defaultSort('created_at','desc');} public static function getPages():array{return ['index'=>Pages\ListSemesters::route('/'),'create'=>Pages\CreateSemester::route('/create'),'edit'=>Pages\EditSemester::route('/{record}/edit')];} }
