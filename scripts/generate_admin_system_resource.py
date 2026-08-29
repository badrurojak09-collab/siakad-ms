from pathlib import Path
root=Path('/home/ubuntu/siakad-laravel/app/Filament/Resources')

def write(rel,text):
 p=root/rel; p.parent.mkdir(parents=True,exist_ok=True); p.write_text(text.strip()+'\n')
write('ActivityLogs/ActivityLogResource.php',r'''<?php
namespace App\Filament\Resources\ActivityLogs;

use App\Filament\Resources\ActivityLogs\Pages\{ListActivityLogs, ViewActivityLog};
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\{Columns\TextColumn, Filters\SelectFilter, Table};
use Spatie\Activitylog\Models\Activity;
use BackedEnum;
use UnitEnum;

class ActivityLogResource extends Resource
{
    protected static ?string $model = Activity::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;
    protected static string|UnitEnum|null $navigationGroup = 'Administrasi Sistem';
    protected static ?string $navigationLabel = 'Log Aktivitas';
    protected static ?string $modelLabel = 'Log Aktivitas';
    protected static ?string $pluralModelLabel = 'Log Aktivitas';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('log_name')->label('Kanal')->badge()->sortable(),
            TextColumn::make('event')->label('Peristiwa')->placeholder('-')->searchable(),
            TextColumn::make('description')->label('Deskripsi')->wrap()->searchable(),
            TextColumn::make('causer.name')->label('Pelaku')->placeholder('Sistem')->searchable(),
            TextColumn::make('subject_type')->label('Jenis Objek')->formatStateUsing(fn (?string $state): string => $state ? class_basename($state) : '-'),
            TextColumn::make('subject_id')->label('ID Objek')->placeholder('-'),
            TextColumn::make('created_at')->label('Waktu')->dateTime('d M Y H:i:s')->sortable(),
        ])->filters([
            SelectFilter::make('log_name')->label('Kanal')->options(fn () => Activity::query()->whereNotNull('log_name')->distinct()->orderBy('log_name')->pluck('log_name', 'log_name')->all()),
            SelectFilter::make('event')->label('Peristiwa')->options(fn () => Activity::query()->whereNotNull('event')->distinct()->orderBy('event')->pluck('event', 'event')->all()),
        ])->actions([
            \Filament\Tables\Actions\ViewAction::make()->label('Lihat'),
        ])->defaultSort('created_at', 'desc')->poll('30s');
    }

    public static function getPages(): array
    {
        return ['index' => ListActivityLogs::route('/'), 'view' => ViewActivityLog::route('/{record}')];
    }
}
''')
write('ActivityLogs/Pages/ListActivityLogs.php',r'''<?php
namespace App\Filament\Resources\ActivityLogs\Pages;
use App\Filament\Resources\ActivityLogs\ActivityLogResource;
use Filament\Resources\Pages\ListRecords;
class ListActivityLogs extends ListRecords { protected static string $resource = ActivityLogResource::class; }
''')
write('ActivityLogs/Pages/ViewActivityLog.php',r'''<?php
namespace App\Filament\Resources\ActivityLogs\Pages;
use App\Filament\Resources\ActivityLogs\ActivityLogResource;
use Filament\Resources\Pages\ViewRecord;
class ViewActivityLog extends ViewRecord { protected static string $resource = ActivityLogResource::class; }
''')
print('generated admin system audit resource')
