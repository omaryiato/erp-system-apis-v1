<?php

namespace App\Models\HR;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;



class EmployeePayrollItem extends Model
{

    use HasFactory;

    protected $table = 'employee_payroll_items';

    public $timestamps = false;

    protected $fillable = [

        'employee_id',
        'payroll_item_type_id',
        'value',
        'effective_from',
        'effective_to'

    ];




    protected $casts = [

        'value'=>'decimal:2',
        'effective_from'=>'date',
        'effective_to'=>'date'

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





    public function payrollItemType()
    {

        return $this->belongsTo(
            PayrollItemType::class
        );

    }


}
