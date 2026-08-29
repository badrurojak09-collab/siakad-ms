<?php
namespace App\Filament\Resources\Curriculums;
use App\Models\Curriculum;
use App\Filament\Resources\Curriculums\Pages;
use Filament\Forms\Components\{Select, TextInput, Textarea};
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\{Actions\DeleteAction, Actions\EditAction, Columns\TextColumn, Filters\SelectFilter, Table};
use BackedEnum; use UnitEnum;
class CurriculumResource extends Resource
{
 protected static ?string $slug='curriculums';
 protected static ?string $model=Curriculum::class; protected static string|BackedEnum|null $navigationIcon=Heroicon::OutlinedBookOpen; protected static string|UnitEnum|null $navigationGroup='Kurikulum & Mata Kuliah'; protected static ?string $navigationLabel='Kurikulum'; protected static ?string $modelLabel='Kurikulum'; protected static ?string $pluralModelLabel='Kurikulum';
 public static function form(Schema $schema): Schema { return $schema->components([Select::make('study_program_id')->label('Program Studi')->relationship('studyProgram','name')->searchable()->preload()->required(), TextInput::make('name')->label('Nama Kurikulum')->required()->maxLength(255), TextInput::make('year')->label('Tahun Kurikulum')->numeric()->minValue(1900)->maxValue((int)date('Y')+5)->required(), Select::make('status')->label('Status')->options(['draft'=>'Draf','active'=>'Aktif','archived'=>'Diarsipkan'])->default('draft')->required(), Textarea::make('description')->label('Deskripsi')->columnSpanFull()]); }
 public static function table(Table $table): Table { return $table->columns([TextColumn::make('name')->label('Nama Kurikulum')->searchable()->sortable(),TextColumn::make('studyProgram.name')->label('Program Studi')->searchable(),TextColumn::make('year')->label('Tahun')->sortable(),TextColumn::make('status')->label('Status')->badge(),TextColumn::make('courses_count')->label('Jumlah Mata Kuliah')->counts('courses')])->filters([SelectFilter::make('status')->label('Status')->options(['draft'=>'Draf','active'=>'Aktif','archived'=>'Diarsipkan'])])->actions([EditAction::make()->label('Ubah'),DeleteAction::make()->label('Hapus')->requiresConfirmation()])->defaultSort('year','desc'); }
 public static function getRelations(): array { return [\App\Filament\Resources\Curriculums\RelationManagers\CoursesRelationManager::class]; }
 public static function getPages(): array { return ['index'=>Pages\ListCurriculums::route('/'),'create'=>Pages\CreateCurriculum::route('/create'),'edit'=>Pages\EditCurriculum::route('/{record}/edit')]; }
}
