<?php

namespace App\Actions\Scheduling;

use App\Models\Schedule;
use App\Models\Room;
use Illuminate\Validation\ValidationException;

class CreateScheduleAction
{
    public function execute(array $data): Schedule
    {
        if (($data['end_time'] ?? '') <= ($data['start_time'] ?? '')) {
            throw ValidationException::withMessages(['end_time' => 'Waktu selesai harus setelah waktu mulai.']);
        }

        if (! empty($data['is_online'])) {
            $data['room_id'] = null;
        } elseif (! empty($data['room_id'])) {
            $room = Room::query()->findOrFail($data['room_id']);
            if (! $room->is_active) {
                throw ValidationException::withMessages(['room_id' => 'Ruang yang dipilih tidak aktif.']);
            }

            $conflict = Schedule::query()
                ->where('room_id', $room->getKey())
                ->where('day_of_week', $data['day_of_week'])
                ->where(function ($query) use ($data): void {
                    $query->where('start_time', '<', $data['end_time'])->where('end_time', '>', $data['start_time']);
                })->exists();
            if ($conflict) throw ValidationException::withMessages(['room_id' => 'Ruang sudah dipakai pada waktu yang beririsan.']);
        }

        if (! empty($data['lecturer_id'])) {
            $conflict = Schedule::query()->where('lecturer_id', $data['lecturer_id'])->where('day_of_week', $data['day_of_week'])->where(function ($query) use ($data): void {
                $query->where('start_time', '<', $data['end_time'])->where('end_time', '>', $data['start_time']);
            })->exists();
            if ($conflict) throw ValidationException::withMessages(['lecturer_id' => 'Dosen sudah memiliki jadwal yang beririsan.']);
        }

        return Schedule::create($data);
    }
}
