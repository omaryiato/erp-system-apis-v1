<?php

namespace App\Services\Attendance;

use App\Models\Attendance\EmployeePayment;
use App\Repositories\Attendance\EmployeePaymentRepository;
use App\Services\ChequeService;
use App\Services\FinancialAccountService;
use Illuminate\Support\Facades\DB;


class EmployeePaymentService
{
    public function __construct(
        protected EmployeePaymentRepository $repository,
        protected ChequeService $chequeService,
        protected FinancialAccountService $financialAccountService,
    ) {}

    public function getAll()
    {
        return $this->repository->getAll();
    }

    public function create(array $data): EmployeePayment
    {
        return DB::transaction(function () use ($data) {

            $cheque_info = null;

            if (isset($data['payment_method']) &&
                $data['payment_method'] == 'cheques' &&
                !isset($data['cheque_id']) ) {

                $cheque_info = $this->chequeService->create(
                    $data['cheque']
                );

                $data['cheques_id'] = $cheque_info->id;
                $data['cheque_amount'] = $cheque_info->amount;
            }

            $payment_info = $this->preparePaymentInfo($data);

            $payment_details = $this->repository->create($payment_info);

            // $this->financialAccountService->updateAccountBalance($payment_info, "employee_payment");

            return $payment_details;
        });
    }

    public function update(
        EmployeePayment $payment,
        array $data
    ): EmployeePayment {

        return DB::transaction(function () use ($payment, $data) {
            $this->chequeService->delete(
                    $payment->cheque_id
                );

            $cheque_info = null;

            if (isset($data['payment_method']) &&
                $data['payment_method'] == 'cheques' &&
                !isset($data['cheque_id'])  ) {

                $cheque_info = $this->chequeService->create(
                    $data['cheque']
                );

                $data['cheques_id'] = $cheque_info->id;
                $data['cheque_amount'] = $cheque_info->amount;
            }

            $payment_info = $this->preparePaymentInfo($data);

            $payment_details = $this->repository->update(
                $payment,
                $payment_info
            );

             // $this->financialAccountService->updateAccountBalance($payment_info, "employee_payment");

            return $payment_details;
        });
    }

    public function delete(
        EmployeePayment $payment
    ): bool {
        return DB::transaction(function () use ($payment) {

            if(isset($payment->cheque_id)){
                $this->chequeService->delete(
                    $payment->cheque_id
                );
            }

            return $this->repository->delete($payment);;
        });

    }

    public function employeePayments(
        $employee,
        ?string $from = null,
        ?string $to = null
    ) {
        return $this->repository
            ->employeePayments(
                $employee,
                $from,
                $to
            );
    }

    public function preparePaymentInfo(array $attendance_request)
    {

        $attendance_data =  [
            'employee_id' => $attendance_request['employee_id'] ?? null,
            'payment_date' => $attendance_request['payment_date'] ?? now(),
            'amount' => $attendance_request['amount'] ?? null,
            'payment_type' => $attendance_request['payment_type'] ?? null,
            'period_start' => $attendance_request['period_start'] ?? 8,
            'period_end' => $attendance_request['period_end'] ?? 0,
            'notes' => $attendance_request['notes'] ?? 'active',
            'payment_method' => $attendance_request['payment_method'] ?? 'cash',
            'cheques_id' => $attendance_request['cheques_id'] ?? null,
            'financial_account_id' => $attendance_request['financial_account_id'] ?? null,
            'cheque_amount' => $expense_request['cheque_amount'] ?? null,
        ];

        return $attendance_data;
    }
}
