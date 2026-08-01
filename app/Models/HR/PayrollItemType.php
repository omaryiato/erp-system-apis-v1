<?php

namespace App\Models\HR;

use App\Models\Core\Company;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class PayrollItemType extends Model
{

    use HasFactory;


    protected $fillable = [

        'company_id',
        'name',
        'item_kind',
        'is_percentage',
        'default_value',
        'is_active'

    ];



    protected $casts = [

        'is_percentage'=>'boolean',
        'is_active'=>'boolean',
        'default_value'=>'decimal:2'

    ];



    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */


    public function company()
    {

        return $this->belongsTo(
            Company::class
        );

    }



    public function employeeItems()
    {

        return $this->hasMany(
            EmployeePayrollItem::class
        );

    }


}
