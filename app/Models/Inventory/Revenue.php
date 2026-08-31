<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Revenue extends Model
{
    protected $table = 'revenues_v1';

    protected $fillable = [
        'revenue_number',
        'project_id',
        'revenue_date',
        'category',
        'description',
        'amount',
        'notes',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'revenue_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(
            Project::class,
            'project_id'
        );
    }

    public function cashTransactions(): HasMany
    {
        return $this->hasMany(
            CashTransaction::class,
            'revenue_id'
        );
    }
}
