<?php

namespace App\Models\Accountant;

use App\Models\Core\Company;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChartOfAccount extends Model
{
    use HasFactory;

    protected $table = 'chart_of_accounts';

    protected $fillable = [
        'company_id',
        'parent_id',
        'code',
        'name',
        'account_type',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(ChartOfAccount::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(ChartOfAccount::class, 'parent_id');
    }

    public function lines(): HasMany
    {
        return $this->hasMany(JournalEntryLine::class, 'account_id');
    }

    /**
     * مجموع الرصيد (مدين - دائن) لهذا الحساب من كل القيود المرحّلة فقط
     */
    public function postedBalance(): float
    {
        return $this->lines()
            ->whereHas('journalEntry', fn ($q) => $q->whereNotNull('posted_at'))
            ->selectRaw('COALESCE(SUM(debit) - SUM(credit), 0) as balance')
            ->value('balance');
    }
}
