<?php

namespace App\Models\Attendance;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PayrollPeriod extends Model
{
    protected $table = 'payroll_periods_v1';

    protected $fillable = [
        'name',
        'period_start',
        'period_end',
        'status',
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // public function payments(): HasMany
    // {
    //     return $this->hasMany(
    //         EmployeePayment::class,
    //         'period_start',
    //         'period_start'
    //     );
    // }
}
