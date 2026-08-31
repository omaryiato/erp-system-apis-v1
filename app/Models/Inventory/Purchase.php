<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Purchase extends Model
{
    protected $table = 'purchases_v1';

    protected $fillable = [
        'supplier_id',
        'purchase_date',
        'reference_number',
        'notes',
        'status',
    ];

    protected $casts = [
        'purchase_date' => 'date',
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(
            Supplier::class,
            'supplier_id'
        );
    }

    public function items(): HasMany
    {
        return $this->hasMany(
            PurchaseItem::class,
            'purchase_id'
        );
    }
}
