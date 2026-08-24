<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;



class User extends Authenticatable
{
    // use SoftDeletes;
    use HasApiTokens;

    protected $table = 'users_v1';

    protected $fillable = [
        'full_name',
        'user_name',
        'phone_number',
        'email_address',
        'password',
        'status',
        'user_type',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected $hidden = [
        'password',
    ];

}

