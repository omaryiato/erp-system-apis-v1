<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Setting extends Model
{
    use HasFactory;

    protected $table = 'settings';

    public $timestamps = false;

    protected $fillable = [
        'company_id',
        'key',
        'value',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * جلب قيمة إعداد معين لشركة معينة بسرعة
     */
    public static function getValue(int $companyId, string $key, $default = null)
    {
        return static::where('company_id', $companyId)
            ->where('key', $key)
            ->value('value') ?? $default;
    }
}