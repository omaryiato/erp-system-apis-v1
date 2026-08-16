<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    protected $table = 'attendance_v1';

    protected $fillable = [
        'employee_id',
        'work_date',
        'check_in',
        'check_out',
        'worked_hours',
        'overtime_hours',
        'daily_amount',
        'overtime_amount',
        'notes',
    ];

    protected $casts = [
        'work_date' => 'date',
        'check_in' => 'datetime',
        'check_out' => 'datetime',
        'worked_hours' => 'decimal:2',
        'overtime_hours' => 'decimal:2',
        'daily_amount' => 'decimal:2',
        'overtime_amount' => 'decimal:2',
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
}
