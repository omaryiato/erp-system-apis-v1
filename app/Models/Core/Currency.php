<?php

namespace App\Models\Core;

use App\Models\Parties\SupplierDetail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Currency extends Model
{
    use HasFactory;

    protected $table = 'currencies';

    public $timestamps = false;

    protected $fillable = [
        'code',
        'name',
        'symbol',
        'decimal_places',
    ];

    protected $casts = [
        'decimal_places' => 'integer',
    ];

    public function companies(): HasMany
    {
        return $this->hasMany(Company::class, 'base_currency_id');
    }

    public function ratesFrom(): HasMany
    {
        return $this->hasMany(ExchangeRate::class, 'from_currency_id');
    }

    public function ratesTo(): HasMany
    {
        return $this->hasMany(ExchangeRate::class, 'to_currency_id');
    }

    public function supplierDetails(): HasMany
    {
        return $this->hasMany(SupplierDetail::class, 'default_currency_id');
    }
}
