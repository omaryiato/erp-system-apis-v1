<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class Sequence extends Model
{
    use HasFactory;

    protected $table = 'sequences';

    public $timestamps = false;

    protected $fillable = [
        'company_id',
        'branch_id',
        'key',
        'prefix',
        'next_number',
        'padding',
    ];

    protected $casts = [
        'next_number' => 'integer',
        'padding' => 'integer',
        
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * يولّد الرقم الجاي بشكل آمن (atomic) ويرجع الرقم كامل مع البريفكس
     * مثال: INV-000123
     */
    public static function nextNumber(int $companyId, string $key, ?int $branchId = null): string
    {
        return DB::transaction(function () use ($companyId, $key, $branchId) {
            $sequence = static::where('company_id', $companyId)
                ->where('key', $key)
                ->where('branch_id', $branchId)
                ->lockForUpdate()
                ->first();

            if (! $sequence) {
                $sequence = static::create([
                    'company_id' => $companyId,
                    'branch_id' => $branchId,
                    'key' => $key,
                    'prefix' => strtoupper(substr($key, 0, 3)),
                    'next_number' => 1,
                    'padding' => 6,
                ]);
            }

            $number = $sequence->next_number;
            $sequence->increment('next_number');

            return $sequence->prefix.'-'.str_pad((string) $number, $sequence->padding, '0', STR_PAD_LEFT);
        });
    }
}