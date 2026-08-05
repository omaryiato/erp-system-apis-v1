<?php

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Position extends Model
{
    use HasFactory;

    protected $table = 'employees';


    protected $fillable = [
        'company_id',
        'title',
        'department_id',
        'is_active',
    ];


    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */


    public function department()
    {
        return $this->belongsTo(Department::class);
    }

}
