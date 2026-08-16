<?php

namespace App\Repositories;

use App\Models\Attendance;
use App\Models\Employee;

class AttendanceRepository
{

    public function getAll()
    {
        return Attendance::latest()
                ->get();
    }

    public function create(array $data): Attendance
    {
        return Attendance::create($data);
    }

    public function update(
        Attendance $attendance,
        array $data
    ): Attendance {
        $attendance->update($data);

        return $attendance->refresh();
    }

    public function find(int $id): Attendance
    {
        return Attendance::findOrFail($id);
    }

    public function employeeAttendance(
        Employee $employee,
        ?string $from = null,
        ?string $to = null
    ) {
        return $employee->attendance()
            ->when(
                $from,
                fn ($q) => $q->whereDate(
                    'work_date',
                    '>=',
                    $from
                )
            )
            ->when(
                $to,
                fn ($q) => $q->whereDate(
                    'work_date',
                    '<=',
                    $to
                )
            )
            ->orderBy('work_date')
            ->get();
    }
}
