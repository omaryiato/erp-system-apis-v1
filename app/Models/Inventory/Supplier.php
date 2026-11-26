<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Supplier extends Model
{
    protected $table = 'suppliers_v1';

    protected $fillable = [
        'supplier_code',
        'name',
        'phone',
        'email',
        'address',
        'tax_number',
        'notes',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];


    public function cashTransactions(): HasManyThrough
    {
        return $this->hasManyThrough(CashTransaction::class, Purchase::class, 'supplier_id', 'reference_id')
            ->where('cash_transactions_v1.transaction_type', 'purchase');
    }
    // public function cashTransactions(): HasMany
    // {
    //     return $this->hasMany(
    //         CashTransaction::class,
    //         'supplier_id'
    //     );
    // }

    public function purchases(): HasMany
    {
        return $this->hasMany(
            Purchase::class,
            'supplier_id'
        );
    }
}
