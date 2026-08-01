<?php

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends Model
{
    use HasFactory;


    protected $fillable = [
        'employee_id_number',
        'full_name',
        'national_id',
        'department_id',
        'position_id',
        'hire_date',
        'termination_date',
        'status',
        'base_salary',
        'shift_id',
        'biometric_code'
    ];


    protected $casts = [
        'hire_date' => 'date',
        'termination_date' => 'date',
        'base_salary' => 'decimal:2'
    ];


    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */


    // public function department()
    // {
    //     return $this->belongsTo(Department::class);
    // }


    // public function position()
    // {
    //     return $this->belongsTo(Position::class);
    // }


    public function attendance()
    {
        return $this->hasMany(Attendance::class);
    }


    public function payrollItems()
    {
        return $this->hasMany(EmployeePayrollItem::class);
    }

}
