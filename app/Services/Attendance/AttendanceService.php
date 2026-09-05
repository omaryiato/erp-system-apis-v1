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

        /*
        * عدد ساعات الدوام العادية في اليوم
        */
        $normalWorkingHours = $employee->daily_worked_hours;

        /*
        * حساب الأجر اليومي
        */
        if ($employee->salary_type === 'daily') {
            $fullDailyAmount = (float) $employee->base_salary;
        } else {
            // الموظف الشهري
            $fullDailyAmount = (float) $employee->base_salary / 30;
        }

        /*
        * قيمة ساعة العمل العادية
        */
        $hourlyRate = $fullDailyAmount / $normalWorkingHours;

        /*
        * حساب الساعات العادية فقط.
        *
        * إذا عمل 4 ساعات:
        * 4 ساعات عادية
        *
        * إذا عمل 12 ساعة:
        * 9 ساعات عادية + 3 ساعات overtime
        */
        // $regularHours = min(
        //     $workedHours,
        //     $normalWorkingHours
        // );

        /*
        * إذا لم يتم إرسال overtime_hours،
        * نحسبه تلقائيًا من worked_hours.
        */
        if ($overtimeHours <= 0 && $workedHours > $normalWorkingHours) {
            $overtimeHours = $workedHours - $normalWorkingHours;
        }

        /*
        * المبلغ المستحق عن الساعات العادية
        */
        $regularAmount =
            $workedHours * $hourlyRate;

        /*
        * overtime_rate هو معامل الساعات:
        *
        * 1   = ساعة OT = ساعة
        * 1.5 = ساعة OT = ساعة ونصف
        * 2   = ساعة OT = ساعتين
        */
        $overtimeRate = (float) (
            $employee->overtime_rate ?? 1
        );

        /*
        * قيمة ساعة overtime
        */
        $overtimeHourlyRate =
            $hourlyRate * $overtimeRate;

        /*
        * مبلغ overtime
        */
        $overtimeAmount =
            $overtimeHours * $overtimeHourlyRate;

        /*
        * إجمالي المبلغ
        */
        // $totalAmount =
        //     $regularAmount + $overtimeAmount;

        $data['worked_hours'] = $workedHours;
        // $data['regular_hours'] = $regularHours;
        $data['overtime_hours'] = $overtimeHours;

        $data['daily_amount'] = round(
            $regularAmount,
            2
        );

        // $data['hourly_rate'] = round(
        //     $hourlyRate,
        //     2
        // );

        // $data['overtime_rate'] = $overtimeRate;

        // $data['overtime_hourly_rate'] = round(
        //     $overtimeHourlyRate,
        //     2
        // );

        $data['overtime_amount'] = round(
            $overtimeAmount,
            2
        );

        // $data['total_amount'] = round(
        //     $totalAmount,
        //     2
        // );

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
