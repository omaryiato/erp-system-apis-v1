<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseAllocation extends Model
{
    protected $table = 'purchase_allocations_v1';

    protected $fillable = [
        'purchase_item_id',
        'project_id',
        'quantity',
        'notes',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
    ];

    public function purchaseItem(): BelongsTo
    {
        return $this->belongsTo(
            PurchaseItem::class,
            'purchase_item_id'
        );
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(
            Project::class,
            'project_id'
        );
    }
}
