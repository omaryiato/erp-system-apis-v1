<?php

namespace App\Services\HR;

use App\Models\HR\PayslipItem;
use App\Repositories\HR\PayslipItemRepository;

class PayslipItemService
{
    public function __construct(
        protected PayslipItemRepository $repository
    ) {}

    public function create(array $data): PayslipItem
    {
        return $this->repository->create($data);
    }

    public function delete(PayslipItem $item): bool
    {
        return $this->repository->delete($item);
    }
}
