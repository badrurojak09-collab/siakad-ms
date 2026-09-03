<?php

namespace App\Filament\Resources\AcademicAdvisors\Pages;

use App\Actions\Academic\UpdateAcademicAdvisorAction;
use App\Filament\Resources\AcademicAdvisors\AcademicAdvisorResource;
use App\Models\AcademicAdvisor;
use Filament\Resources\Pages\EditRecord;

class EditAcademicAdvisor extends EditRecord
{
    protected static string $resource = AcademicAdvisorResource::class;

    protected function handleRecordUpdate($record, array $data): AcademicAdvisor
    {
        return app(UpdateAcademicAdvisorAction::class)->execute($record, $data, auth()->user());
    }
}
