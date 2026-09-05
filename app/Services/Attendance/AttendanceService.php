<?php

namespace App\Services\Attendance;

use App\Models\Attendance\Attendance;
use App\Repositories\Attendance\AttendanceRepository;
use App\Models\Attendance\Employee;

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

        return $this->repository->create($this->prepareAttendanceInfo($data));
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
            $this->prepareAttendanceInfo($data)
        );
    }

    public function delete(
        Attendance $attendance
    ): void {
        $this->repository->delete($attendance);
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

    public function prepareAttendanceInfo(array $attendance_request)
    {

        $attendance_data =  [
            'employee_id' => $attendance_request['employee_id'] ?? null,
            'work_date' => $attendance_request['work_date'] ?? null,
            'check_in' => $attendance_request['check_in'] ?? now(),
            'check_out' => $attendance_request['check_out'] ?? null,
            'worked_hours' => $attendance_request['worked_hours'] ?? 8,
            'overtime_hours' => $attendance_request['overtime_hours'] ?? 0,
            'daily_amount' => $attendance_request['daily_amount'] ?? 0,
            'overtime_amount' => $attendance_request['overtime_amount'] ?? 0,
            'notes' => $attendance_request['notes'] ?? 'active',
        ];

        return $attendance_data;
    }

    public function getAllHistory()
    {
        return $this->repository->getAllHistory();
    }

    public function employeeHistoryAttendance(
        Employee $employee,
        ?string $from = null,
        ?string $to = null
    ) {
        return $this->repository->employeeHistoryAttendance(
            $employee,
            $from,
            $to
        );
    }

}
