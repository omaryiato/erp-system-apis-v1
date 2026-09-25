<?php

namespace App\Services;

use App\Models\Cheque;
use App\Repositories\ChequeRepository;
use App\Repositories\FinancialAccountRepository;
use Illuminate\Validation\ValidationException;

class ChequeService
{
    public function __construct(
        protected ChequeRepository $repository,
        protected FinancialAccountRepository $accountRepository
    ) {
    }

    public function getAll()
    {
        return $this->repository->getAll();
    }

    public function getDetails(Cheque $cheque): Cheque
    {
        return $this->repository->getDetails($cheque);
    }

    public function create(array $cheque_request): Cheque
    {
        $account = $this->accountRepository->getDetails(
            $cheque_request['financial_account_id']
        );

        if (!$account) {
            throw ValidationException::withMessages([
                'financial_account_id' => [
                    'Financial account not found.'
                ],
            ]);
        }

        if (!$account->is_active) {
            throw ValidationException::withMessages([
                'financial_account_id' => [
                    'Financial account is inactive.'
                ],
            ]);
        }

        /*
         * Cheques should be linked to a bank account.
         */
        if ($account->account_type !== 'BANK') {
            throw ValidationException::withMessages([
                'financial_account_id' => [
                    'Cheque must be linked to a BANK account.'
                ],
            ]);
        }

        if (
            $this->repository->chequeNumberExists(
                $cheque_request['cheque_number'],
                $cheque_request['financial_account_id']
            )
        ) {
            throw ValidationException::withMessages([
                'cheque_number' => [
                    'Cheque number already exists for this financial account.'
                ],
            ]);
        }

        return $this->repository->create($this->prepareChequeInfo($cheque_request))
            ->load('financialAccount');
    }

    public function update(
        Cheque $cheque,
        array $cheque_request
    ): Cheque {

        /*
         * Do not allow editing finalized cheques.
         */
        if (
            in_array(
                $cheque->status,
                ['CLEARED', 'CANCELLED']
            )
        ) {
            throw ValidationException::withMessages([
                'cheque' => [
                    'A cleared or cancelled cheque cannot be edited.'
                ],
            ]);
        }

        if (isset($cheque_request['financial_account_id'])) {

            $account = $this->accountRepository->getDetails(
                $cheque_request['financial_account_id']
            );

            if (!$account) {
                throw ValidationException::withMessages([
                    'financial_account_id' => [
                        'Financial account not found.'
                    ],
                ]);
            }

            if ($account->account_type !== 'BANK') {
                throw ValidationException::withMessages([
                    'financial_account_id' => [
                        'Cheque must be linked to a BANK account.'
                    ],
                ]);
            }
        }

        $accountId =
            $cheque_request['financial_account_id']
            ?? $cheque->financial_account_id;

        if (
            isset($cheque_request['cheque_number']) &&
            $this->repository->chequeNumberExists(
                $cheque_request['cheque_number'],
                $accountId,
                $cheque->id
            )
        ) {
            throw ValidationException::withMessages([
                'cheque_number' => [
                    'Cheque number already exists for this financial account.'
                ],
            ]);
        }

        /*
         * Status must not be changed through normal update.
         */
        unset($cheque_request['status']);

        return $this->repository->update(
            $cheque,
            $this->prepareChequeInfo($cheque_request)
        );
    }

    public function delete(Cheque $cheque): bool
    {

        if (
            in_array(
                $cheque->status,
                ['CLEARED', 'DEPOSITED']
            )
        ) {
            throw ValidationException::withMessages([
                'cheque' => [
                    'A deposited or cleared cheque cannot be deleted.'
                ],
            ]);
        }

        return $this->repository->delete($cheque);
    }

    public function prepareChequeInfo(array $cheque_request)
    {

        $cheque_data =  [
            'cheque_number' => $cheque_request['cheque_number'] ?? null,
            'cheque_type' => $cheque_request['cheque_type'] ?? null,
            'financial_account_id' => $cheque_request['financial_account_id'],
            'party_type' => $cheque_request['party_type'] ?? 0,
            'party_id' => $cheque_request['party_id'] ?? null,
            'issue_date' => $cheque_request['issue_date'] ?? null,
            'status' => $cheque_request['status'] ?? 'PENDING',
            'description' => $cheque_request['description'] ?? null,
        ];

        return $cheque_data;
    }
}
