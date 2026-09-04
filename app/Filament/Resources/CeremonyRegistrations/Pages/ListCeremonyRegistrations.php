<?php
namespace App\Filament\Resources\CeremonyRegistrations\Pages;

use App\Filament\Resources\CeremonyRegistrations\CeremonyRegistrationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCeremonyRegistrations extends ListRecords
{
    protected static string $resource = CeremonyRegistrationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Tambah Pendaftaran Wisuda'),
        ];
    }
}
