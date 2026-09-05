<?php

namespace App\Services\Attendance;

use App\Models\Attendance\Employee;
use App\Models\Attendance\PayrollPeriod;
use App\Repositories\Attendance\PayrollPeriodRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PayrollPeriodService
{
    public function __construct(
        protected PayrollPeriodRepository $repository
    ) {}

    public function all()
    {
        return $this->repository->all();
    }

    public function find(int $id): PayrollPeriod
    {
        return $this->repository->find($id);
    }

    public function create(array $data): PayrollPeriod
    {
        return $this->repository->create($this->preparePayrollInfo($data));
    }

    public function close(PayrollPeriod $period): PayrollPeriod
    {
        if ($period->status === 'closed') {
            throw ValidationException::withMessages([
                'status' => 'Payroll period is already closed.',
            ]);
        }

        return $this->repository->update(
            $period,
            ['status' => 'closed']
        );
    }

    public function report(
        PayrollPeriod $period,
        ?int $employeeId = null
    ) {
        $query = Employee::query()
            ->where('status', 'active')
            ->when(
                $employeeId,
                fn ($q) => $q->where('id', $employeeId)
            );

        $employees = $query->get();

        return $employees->map(function ($employee) use ($period) {

            $attendance = $employee->attendanceHistory()
                ->whereBetween(
                    'work_date',
                    [
                        $period->period_start,
                        $period->period_end,
                    ]
                )
                ->get();

            // $transactions = $employee->transactions()
            //     ->whereBetween(
            //         'transaction_date',
            //         [
            //             $period->period_start,
            //             $period->period_end,
            //         ]
            //     )
            //     ->get();

            $payments = $employee->payments()
                ->whereBetween(
                    'payment_date',
                    [
                        $period->period_start,
                        $period->period_end,
                    ]
                )
                ->get();

            $workedDays = $attendance->count();

            $workedHours = $attendance
                ->sum('worked_hours');

            $overtimeHours = $attendance
                ->sum('overtime_hours');

            $basicAmount = $attendance
                ->sum('daily_amount');

            $overtimeAmount = $attendance
                ->sum('overtime_amount');

            // $advances = $transactions
            //     ->where('type', 'advance')
            //     ->sum('amount');

            // $deductions = $transactions
            //     ->where('type', 'deduction')
            //     ->sum('amount');

            $advances = $payments
                ->where('payment_type', 'advance')
                ->sum('amount');

            $deductions = $payments
                ->where('payment_type', 'deduction')
                ->sum('amount');

            $salary = $payments
                ->where('payment_type', 'salary')
                ->sum('amount');

            $other = $payments
                ->where('payment_type', 'other')
                ->sum('amount');

            $totalEarned =
                $basicAmount +
                $overtimeAmount;

            $netDue =
                $totalEarned -
                $advances -
                $salary -
                $other -
                $deductions;

            $totalPaid = $payments->sum('amount');

            $remaining =
                $netDue -
                $totalPaid;

            return [
                'employee' => [
                    'id' => $employee->id,
                    'employee_number' =>
                        $employee->employee_number,
                    'full_name' =>
                        $employee->full_name,
                ],

                'attendance' => [
                    'worked_days' => $workedDays,
                    'worked_hours' => round(
                        $workedHours,
                        2
                    ),
                    'overtime_hours' => round(
                        $overtimeHours,
                        2
                    ),
                ],

                'earnings' => [
                    'basic_amount' => round(
                        $basicAmount,
                        2
                    ),
                    'overtime_amount' => round(
                        $overtimeAmount,
                        2
                    ),
                    'total_earned' => round(
                        $totalEarned,
                        2
                    ),
                ],

                'deductions' => [
                    'salary' => round(
                        $salary,
                        2
                    ),
                    'other' => round(
                        $other,
                        2
                    ),
                    'advances' => round(
                        $advances,
                        2
                    ),
                    'deductions' => round(
                        $deductions,
                        2
                    ),
                    'net_due' => round(
                        $netDue,
                        2
                    ),
                ],

                'payments' => [

                    'total_paid' => round(
                        $totalPaid,
                        2
                    ),

                    'remaining' => round(
                        $remaining,
                        2
                    ),
                ],
            ];
        });
    }


    public function preparePayrollInfo(array $attendance_request)
    {

        $attendance_data =  [
            'name' => $attendance_request['name'] ?? null,
            'period_start' => $attendance_request['period_start'] ?? null,
            'period_end' => $attendance_request['period_end'] ?? null,
        ];

        return $attendance_data;
    }

    public function delete(
        PayrollPeriod $period
    ): void {
        $this->repository->delete($period);
    }
}
