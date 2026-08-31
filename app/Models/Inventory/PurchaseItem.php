<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PurchaseItem extends Model
{
    protected $table = 'purchase_items_v1';

    protected $fillable = [
        'purchase_id',
        'item_id',
        'quantity',
        'unit_price',
        'notes',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    public function purchase(): BelongsTo
    {
        return $this->belongsTo(
            Purchase::class,
            'purchase_id'
        );
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(
            Item::class,
            'item_id'
        );
    }

    public function allocations(): HasMany
    {
        return $this->hasMany(
            PurchaseAllocation::class,
            'purchase_item_id'
        );
    }
}
