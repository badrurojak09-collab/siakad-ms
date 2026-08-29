<?php

namespace App\Filament\Resources\Schedules\Pages;

use App\Actions\Scheduling\CreateScheduleAction;
use App\Filament\Resources\Schedules\ScheduleResource;
use App\Models\Schedule;
use Filament\Resources\Pages\CreateRecord;

class CreateSchedule extends CreateRecord
{
    protected static string $resource = ScheduleResource::class;

    protected function handleRecordCreation(array $data): Schedule
    {
        return app(CreateScheduleAction::class)->execute($data);
    }
}
