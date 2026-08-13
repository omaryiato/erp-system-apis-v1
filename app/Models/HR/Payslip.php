<?php

namespace App\Models\HR;

use App\Models\HR\Employee;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Payslip extends Model
{
    protected $fillable = [
        'payroll_period_id',
        'employee_id',
        'base_salary',
        'total_allowances',
        'total_deductions',
        'net_salary',
        'status',
        'generated_at',
    ];

    protected $casts = [
        'base_salary'      => 'decimal:2',
        'total_allowances' => 'decimal:2',
        'total_deductions' => 'decimal:2',
        'net_salary'       => 'decimal:2',
        'generated_at'     => 'datetime',
    ];

    public function payrollPeriod(): BelongsTo
    {
        return $this->belongsTo(
            PayrollPeriod::class,
            'payroll_period_id'
        );
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(
            Employee::class,
            'employee_id'
        );
    }

    public function items(): HasMany
    {
        return $this->hasMany(PayslipItem::class);
    }
}
