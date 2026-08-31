<?php

namespace App\Models\Attendance;

use App\Models\Inventory\CashTransaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class EmployeePayment extends Model
{
    protected $table = 'employee_payments_v1';

    protected $fillable = [
        'employee_id',
        'payment_date',
        'amount',
        'payment_type',
        'period_start',
        'period_end',
        'notes',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'period_start' => 'date',
        'period_end' => 'date',
        'amount' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(
            Employee::class,
            'employee_id'
        );
    }

    public function cashTransaction(): HasOne
    {
        return $this->hasOne(
            CashTransaction::class,
            'employee_payment_id'
        );
    }
}
