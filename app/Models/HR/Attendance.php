<?php

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Attendance extends Model
{

    use HasFactory;


    protected $fillable = [

        'employee_id',
        'work_date',
        'check_in',
        'check_out',
        'source',
        'status',
        'late_minutes',
        'notes',
        'created_by'

    ];



    protected $casts = [

        'work_date'=>'date',
        'check_in'=>'datetime',
        'check_out'=>'datetime'

    ];



    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */


    public function employee()
    {
        return $this->belongsTo(
            Employee::class
        );
    }


}
