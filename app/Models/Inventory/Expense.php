<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Expense extends Model
{
    protected $table = 'expenses_v1';

    protected $fillable = [
        'expense_number',
        'expense_date',
        'category_id',
        'description',
        'amount',
        'notes',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'expense_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function cashTransactions(): HasMany
    {
        return $this->hasMany(
            CashTransaction::class,
            'expense_id'
        );
    }

    public function expensesCategory(): BelongsTo
    {
        return $this->belongsTo(
            ExpensesCategory::class,
            'category_id',
            'id'
        );
    }
}
