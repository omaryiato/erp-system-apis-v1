<?php

namespace App\Repositories\HR;

use App\Models\HR\PayslipItem;
use Illuminate\Database\Eloquent\Collection;

class PayslipItemRepository
{
    public function all(): Collection
    {
        return PayslipItem::query()
            ->with([
                'payslip',
                'payrollItemType',
            ])
            ->latest('created_at')
            ->get();
    }

    public function find(int $id): PayslipItem
    {
        return PayslipItem::with([
            'payslip',
            'payrollItemType',
        ])->findOrFail($id);
    }

    public function create(array $data): PayslipItem
    {
        return PayslipItem::create($data);
    }

    public function update(
        PayslipItem $item,
        array $data
    ): PayslipItem {
        $item->update($data);

        return $item->refresh();
    }

    public function delete(PayslipItem $item): bool
    {
        return $item->delete();
    }
}
