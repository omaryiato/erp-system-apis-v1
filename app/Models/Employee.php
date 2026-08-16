<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    protected $table = 'employees_v1';

    protected $fillable = [
        'employee_number',
        'full_name',
        'phone',
        'salary_type',
        'base_salary',
        'overtime_rate',
        'status',
        'hire_date',
        'termination_date',
    ];

    protected $casts = [
        'base_salary' => 'decimal:2',
        'overtime_rate' => 'decimal:2',
        'hire_date' => 'date',
        'termination_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function attendance(): HasMany
    {
        return $this->hasMany(
            Attendance::class,
            'employee_id'
        );
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(
            EmployeeTransaction::class,
            'employee_id'
        );
    }

    public function payments(): HasMany
    {
        return $this->hasMany(
            EmployeePayment::class,
            'employee_id'
        );
    }
}
