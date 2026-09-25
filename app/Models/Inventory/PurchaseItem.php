<?php

namespace App\Models\Inventory;

use App\Models\Cheque;
use App\Models\FinancialAccount;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PurchaseItem extends Model
{
    protected $table = 'purchase_items_v1';

    protected $fillable = [
        'purchase_id',
        'item_id',
        'quantity',
        'unit_price',
        'notes',
        'purchase_type',
        'total_amount',
        'cheques_id',
        'financial_account_id',
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

    public function cheques(): HasMany
    {
        return $this->hasMany(
            Cheque::class,
            'cheques_id'
        );
    }

    public function financialAccount(): BelongsTo
    {
        return $this->belongsTo(
            FinancialAccount::class,
            'financial_account_id',
            'id'
        );
    }

    public function cheque(): BelongsTo
    {
        return $this->belongsTo(
            Cheque::class,
            'cheque_id',
            'id'
        );
    }
}
