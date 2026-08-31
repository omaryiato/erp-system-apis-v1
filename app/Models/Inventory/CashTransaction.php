<?php

namespace App\Models\Inventory;

use App\Models\Attendance\EmployeePayment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CashTransaction extends Model
{
    protected $table = 'cash_transactions_v1';

    protected $fillable = [
        'transaction_number',
        'transaction_type',
        'project_id',
        'supplier_id',
        'expense_id',
        'revenue_id',
        'employee_payment_id',
        'purchase_order_id',
        'amount',
        'transaction_date',
        'description',
        'notes',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(
            Project::class,
            'project_id'
        );
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(
            Supplier::class,
            'supplier_id'
        );
    }

    public function expense(): BelongsTo
    {
        return $this->belongsTo(
            Expense::class,
            'expense_id'
        );
    }

    public function revenue(): BelongsTo
    {
        return $this->belongsTo(
            Revenue::class,
            'revenue_id'
        );
    }

    public function employeePayment(): BelongsTo
    {
        return $this->belongsTo(
            EmployeePayment::class,
            'employee_payment_id'
        );
    }

    public function purchase(): BelongsTo
    {
        return $this->belongsTo(
            Purchase::class,
            'purchase_id'
        );
    }
}
