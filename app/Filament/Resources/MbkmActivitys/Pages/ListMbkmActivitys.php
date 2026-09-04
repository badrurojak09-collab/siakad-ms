<?php
namespace App\Filament\Resources\MbkmActivitys\Pages;

use App\Filament\Resources\MbkmActivitys\MbkmActivityResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMbkmActivitys extends ListRecords
{
    protected static string $resource = MbkmActivityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Tambah Aktivitas MBKM'),
        ];
    }
}
