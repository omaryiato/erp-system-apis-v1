<?php

namespace App\Http\Resources\Attendance;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PayslipItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'payslip_id' => $this->payslip_id,

            'payroll_item_type_id' =>
                $this->payroll_item_type_id,

            'item_name' => $this->item_name,

            'item_kind' => $this->item_kind,

            'amount' => $this->amount,
        ];
    }
}
