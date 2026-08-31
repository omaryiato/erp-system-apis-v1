<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $table = 'categories_v1';

    protected $fillable = [
        'name',
        'description',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(Item::class, 'category_id');
    }

    public function snapshots(): HasMany
    {
        return $this->hasMany(ItemSnapshot::class, 'category_id');
    }
}
