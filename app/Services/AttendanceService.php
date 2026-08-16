<?php

namespace App\Services;

use App\Models\Attendance;
use App\Repositories\AttendanceRepository;
use App\Models\Employee;

class AttendanceService
{
    public function __construct(
        protected AttendanceRepository $repository
    ) {}

    public function getAll()
    {
        return $this->repository->getAll();
    }

    public function create(array $data): Attendance
    {
        $employee = Employee::findOrFail(
            $data['employee_id']
        );

        $data = $this->calculateAmounts(
            $employee,
            $data
        );

        return $this->repository->create($data);
    }

    public function update(
        Attendance $attendance,
        array $data
    ): Attendance {
        $employee = $attendance->employee;

        $data = $this->calculateAmounts(
            $employee,
            $data
        );

        return $this->repository->update(
            $attendance,
            $data
        );
    }

    public function employeeAttendance(
        Employee $employee,
        ?string $from = null,
        ?string $to = null
    ) {
        return $this->repository->employeeAttendance(
            $employee,
            $from,
            $to
        );
    }

    private function calculateAmounts(
        Employee $employee,
        array $data
    ): array {
        $workedHours = (float) (
            $data['worked_hours'] ?? 0
        );

        $overtimeHours = (float) (
            $data['overtime_hours'] ?? 0
        );

        if ($employee->salary_type === 'daily') {
            $dailyAmount = $employee->base_salary;
        } else {
            // بالنسبة للموظف الشهري
            // يمكن لاحقًا تقسيم الراتب على عدد أيام العمل.
            $dailyAmount = $employee->base_salary / 30;
        }

        $overtimeAmount =
            $overtimeHours * $employee->overtime_rate;

        $data['worked_hours'] = $workedHours;
        $data['overtime_hours'] = $overtimeHours;
        $data['daily_amount'] = $dailyAmount;
        $data['overtime_amount'] = $overtimeAmount;

        return $data;
    }
}
