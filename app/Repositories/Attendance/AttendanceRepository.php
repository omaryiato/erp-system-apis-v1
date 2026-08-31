<?php

namespace App\Repositories\Attendance;

use App\Models\Attendance\Attendance;
use App\Models\Attendance\AttendanceHistory;
use App\Models\Attendance\Employee;
use Carbon\Carbon;


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

    public function delete(
        Attendance $attendance
    ): void {
        $attendance->delete();
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

        $query = $employee->attendance();

        if ($from) {
            $fromDate = Carbon::createFromFormat('m/Y', $from)->startOfMonth();

            $query->whereDate('work_date', '>=', $fromDate);
        }

        if ($to) {
            $toDate = Carbon::createFromFormat('m/Y', $to)->endOfMonth();

            $query->whereDate('work_date', '<=', $toDate);
        }

        return $query
            ->orderBy('work_date')
            ->get();
    }


    // public function employeeAttendance(
    //     Employee $employee,
    //     ?string $from = null,
    //     ?string $to = null
    // ) {
    //     return $employee->attendance()
    //         ->when(
    //             $from,
    //             fn ($q) => $q->whereDate(
    //                 'work_date',
    //                 '>=',
    //                 $from
    //             )
    //         )
    //         ->when(
    //             $to,
    //             fn ($q) => $q->whereDate(
    //                 'work_date',
    //                 '<=',
    //                 $to
    //             )
    //         )
    //         ->orderBy('work_date')
    //         ->get();
    // }



    public function getAllHistory()
    {
        return AttendanceHistory::latest()
                ->get();
    }
    // public function employeeHistoryAttendance(
    //     Employee $employee,
    //     ?string $from = null,
    //     ?string $to = null
    // ) {
    //     return $employee->attendanceHistory()
    //         ->when(
    //             $from,
    //             fn ($q) => $q->whereDate(
    //                 'work_date',
    //                 '>=',
    //                 $from
    //             )
    //         )
    //         ->when(
    //             $to,
    //             fn ($q) => $q->whereDate(
    //                 'work_date',
    //                 '<=',
    //                 $to
    //             )
    //         )
    //         ->orderBy('work_date')
    //         ->get();
    // }


    public function employeeHistoryAttendance(
        Employee $employee,
        ?string $from = null,
        ?string $to = null
    ) {
        $query = $employee->attendanceHistory();

        if ($from) {
            $fromDate = Carbon::createFromFormat('m/Y', $from)->startOfMonth();

            $query->whereDate('work_date', '>=', $fromDate);
        }

        if ($to) {
            $toDate = Carbon::createFromFormat('m/Y', $to)->endOfMonth();

            $query->whereDate('work_date', '<=', $toDate);
        }

        return $query
            ->orderBy('work_date')
            ->get();
    }
}
