<?php

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Department extends Model
{
    use HasFactory;

    protected $table = 'departments';

    protected $fillable = [
        'company_id',
        'name',
        'parent_id',
        'is_active',
        'created_at',
    ];


    protected $casts = [
        'created_at' => 'datetime',
    ];


    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */


    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function position()
    {
        return $this->hassMany(Position::class);
    }

}
