<?php

namespace App\Services\Inventory;

use App\Models\Inventory\CashTransaction;
use App\Repositories\Inventory\CashTransactionRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Services\ChequeService;


class CashTransactionService
{
    public function __construct(
        private CashTransactionRepository $repository,
        protected ChequeService $chequeService,
    ) {}

    public function getAll()
    {
        return  $this->repository->getAll();
    }


    public function create(array $data, int $actorId): CashTransaction
    {
        return DB::transaction(function () use ($data, $actorId) {
            if (isset($data['payment_method']) &&
                $data['payment_method'] == 'cheques' &&
                !isset($data['cheque_id']) ) {

                if (! isset($data['cheque'])) {
                    throw ValidationException::withMessages(['payment_method' => 'Cheque details are required.']);
                }

                $cheque_info = $this->chequeService->create(
                    $data['cheque']
                );

                $data['cheques_id'] = $cheque_info->id;
                $data['cheque_amount'] = $cheque_info->amount;
            }

            $this->validatePayment($data);

            // $data['transaction_number'] =
            //     $this->generateTransactionNumber();

            $data['created_by'] = $actorId;
            unset($data['updated_by']);

            return $this->repository->create($data);
        });
    }

    public function update(CashTransaction $transaction, array $data, int $actorId): CashTransaction
    {
        return DB::transaction(function () use ($transaction, $data, $actorId): CashTransaction {
            $transaction = $this->repository->lockById($transaction->id);

            if ($transaction->employee_payment_id !== null
                || ! in_array($transaction->transaction_type, ['expense', 'revenue', 'purchase'], true)) {
                throw ValidationException::withMessages([
                    'transaction_type' => 'This transaction cannot be edited through this endpoint.',
                ]);
            }

            if ($data['payment_method'] !== $transaction->payment_method
                && in_array('cheques', [$data['payment_method'], $transaction->payment_method], true)) {
                throw ValidationException::withMessages([
                    'payment_method' => 'This endpoint cannot change a cheque payment method.',
                ]);
            }

            $this->validatePayment($data, $transaction->id);
            $data['updated_by'] = $actorId;

            return $this->repository->update($transaction, $data);
        });
    }

    private function validatePayment(array $data, ?int $exceptId = null): void
    {
        $type = $data['transaction_type'];
        $referenceId = (int) $data['reference_id'];
        $expected = $this->repository->lockReferenceAmount($type, $referenceId);
        $paid = $this->repository->sumForReference($type, $referenceId, $exceptId);
        $remaining = round((float) $expected - (float) $paid, 2);

        if ((float) $data['amount'] > $remaining) {
            throw ValidationException::withMessages([
                'amount' => 'Payment amount exceeds the remaining amount.',
            ]);
        }
    }

    private function generateTransactionNumber(): string
    {
        $last = CashTransaction::query()
            ->latest('id')
            ->value('id');

        return 'TRX-' .
            str_pad(
                ($last ?? 0) + 1,
                6,
                '0',
                STR_PAD_LEFT
            );
    }
}
