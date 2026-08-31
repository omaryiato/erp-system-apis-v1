<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Item extends Model
{
    protected $table = 'items_v1';

    protected $fillable = [
        'category_id',
        'name',
        'code',
        'unit',
        'description',
        'current_unit_price',
        'minimum_stock',
        'status',
    ];

    protected $casts = [
        'current_unit_price' => 'decimal:2',
        'minimum_stock' => 'decimal:3',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(
            Category::class,
            'category_id'
        );
    }

    public function snapshots(): HasMany
    {
        return $this->hasMany(
            ItemSnapshot::class,
            'item_id'
        );
    }

    public function purchaseItems(): HasMany
    {
        return $this->hasMany(
            PurchaseItem::class,
            'item_id'
        );
    }
}
