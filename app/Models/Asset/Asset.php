<?php

namespace App\Models\Asset;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Asset extends Model
{
    protected $table = 'assets_v1';

    protected $fillable = [
        'asset_code',
        'asset_name',
        'category_id',
        'description',
        'serial_number',
        'model',
        'manufacturer',
        'purchase_date',
        'purchase_cost',
        'status',
        'location',
        'warranty_expiry_date',
        'notes',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'warranty_expiry_date' => 'date',
        'purchase_cost' => 'decimal:2',
        'current_value' => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function category(): BelongsTo
    {
        return $this->belongsTo(
            AssetCategory::class,
            'category_id'
        );
    }

    public function maintenances(): HasMany
    {
        return $this->hasMany(
            AssetMaintenance::class,
            'asset_id'
        );
    }

    public function expenses(): HasMany
    {
        return $this->hasMany(
            AssetExpense::class,
            'asset_id'
        );
    }
}
