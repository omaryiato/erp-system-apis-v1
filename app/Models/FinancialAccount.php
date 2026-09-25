<?php

namespace App\Models;

use App\Models\Attendance\EmployeePayment;
use App\Models\Inventory\CashTransaction;
use App\Models\Inventory\PurchaseItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FinancialAccount extends Model
{
    protected $table = 'financial_accounts_v1';

    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'name_ar',
        'account_type',
        'account_number',
        'iban',
        'bank_name',
        'currency',
        'opening_balance',
        'current_balance',
        'is_active',
        'description',
    ];

    protected $casts = [
        'opening_balance' => 'decimal:3',
        'current_balance' => 'decimal:3',
        'is_active' => 'boolean',
    ];

    public function cheques(): HasMany
    {
        return $this->hasMany(
            Cheque::class,
            'financial_account_id',
            'id'
        );
    }

    public function cashTransactions(): HasMany
    {
        return $this->hasMany(
            CashTransaction::class,
            'financial_account_id',
            'id'
        );
    }

    public function purchaseItems(): HasMany
    {
        return $this->hasMany(
            PurchaseItem::class,
            'financial_account_id',
            'id'
        );
    }

    public function employeePayments(): HasMany
    {
        return $this->hasMany(
            EmployeePayment::class,
            'financial_account_id',
            'id'
        );
    }
}
