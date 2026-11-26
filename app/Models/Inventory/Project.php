<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Project extends Model
{
    protected $table = 'projects_v1';

    protected $fillable = [
        'project_code',
        'project_name',
        'customer_name',
        'phone',
        'email',
        'address',
        'description',
        'start_date',
        'end_date',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];


    public function purchaseAllocations(): HasMany
    {
        return $this->hasMany(
            PurchaseAllocation::class,
            'project_id'
        );
    }

    public function revenues(): HasMany
    {
        return $this->hasMany(
            Revenue::class,
            'project_id'
        );
    }

    public function cashTransactions(): HasManyThrough
    {
        return $this->hasManyThrough(CashTransaction::class, Revenue::class, 'project_id', 'reference_id')
            ->where('cash_transactions_v1.transaction_type', 'revenue');
    }
    // public function cashTransactions(): HasMany
    // {
    //     return $this->hasMany(
    //         CashTransaction::class,
    //         'project_id'
    //     );
    // }


}
