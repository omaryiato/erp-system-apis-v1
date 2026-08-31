<?php

namespace App\Models\Inventory;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemSnapshot extends Model
{
    public $timestamps = false;

    protected $table = 'item_snapshots_v1';

    protected $fillable = [
        'item_id',
        'category_id',
        'name',
        'code',
        'unit',
        'description',
        'unit_price',
        'minimum_stock',
        'status',
        'change_type',
        'changed_by',
        'created_at',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'minimum_stock' => 'decimal:3',
        'created_at' => 'datetime',
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(
            Item::class,
            'item_id'
        );
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(
            Category::class,
            'category_id'
        );
    }

    public function changedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'changed_by'
        );
    }
}
