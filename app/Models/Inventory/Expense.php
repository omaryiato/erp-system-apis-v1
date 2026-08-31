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
        'project_id',
        'supplier_id',
        'expense_date',
        'category',
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

    public function project(): BelongsTo
    {
        return $this->belongsTo(
            Project::class,
            'project_id'
        );
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(
            Supplier::class,
            'supplier_id'
        );
    }

    public function cashTransactions(): HasMany
    {
        return $this->hasMany(
            CashTransaction::class,
            'expense_id'
        );
    }
}
