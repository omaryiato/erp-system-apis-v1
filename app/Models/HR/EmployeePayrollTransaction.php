<?php

namespace App\Models\HR;

use App\Models\HR\Employee;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeePayrollTransaction extends Model
{
    protected $fillable = [
        'employee_id',
        'type',
        'amount',
        'remaining_amount',
        'installment_amount',
        'transaction_date',
        'description',
        'status',
    ];

    protected $casts = [
        'amount'             => 'decimal:2',
        'remaining_amount'  => 'decimal:2',
        'installment_amount' => 'decimal:2',
        'transaction_date'  => 'date',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(
            Employee::class,
            'employee_id'
        );
    }
}
