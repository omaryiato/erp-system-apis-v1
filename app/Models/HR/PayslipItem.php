<?php

namespace App\Models\HR;

use App\Models\HR\PayrollItemType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PayslipItem extends Model
{
    protected $fillable = [
        'payslip_id',
        'payroll_item_type_id',
        'item_name',
        'item_kind',
        'amount',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function payslip(): BelongsTo
    {
        return $this->belongsTo(
            Payslip::class,
            'payslip_id'
        );
    }

    public function payrollItemType(): BelongsTo
    {
        return $this->belongsTo(
            PayrollItemType::class,
            'payroll_item_type_id'
        );
    }
}
