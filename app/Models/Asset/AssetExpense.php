<?php

namespace App\Models\Asset;

use App\Models\Inventory\Supplier;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssetExpense extends Model
{
    protected $table = 'asset_expenses_v1';

    protected $fillable = [
        'asset_id',
        'expense_date',
        'expense_type',
        'description',
        'amount',
        'supplier_id',
        'payment_method',
        'reference_number',
        'notes',
    ];

    protected $casts = [
        'expense_date' => 'date',
        'amount' => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function asset(): BelongsTo
    {
        return $this->belongsTo(
            Asset::class,
            'asset_id'
        );
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(
            Supplier::class,
            'supplier_id'
        );
    }
}
