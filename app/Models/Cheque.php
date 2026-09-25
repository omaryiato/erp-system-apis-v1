<?php

namespace App\Models;

use App\Models\Attendance\EmployeePayment;
use App\Models\Inventory\CashTransaction;
use App\Models\Inventory\PurchaseItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Cheque extends Model
{
    protected $table = 'cheques_v1';

    protected $primaryKey = 'id';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'cheque_number',
        'cheque_type',
        'financial_account_id',
        'party_type',
        'party_id',
        'amount',
        'issue_date',
        'due_date',
        'status',
        'description',
    ];

    protected $casts = [
        'amount' => 'decimal:3',
        'issue_date' => 'date',
        'due_date' => 'date',
    ];

    public function financialAccount(): BelongsTo
    {
        return $this->belongsTo(
            FinancialAccount::class,
            'financial_account_id',
            'id'
        );
    }


    public function cashTransactions(): HasOne
    {
        return $this->hasOne(
            CashTransaction::class,
            'financial_account_id',
            'id'
        );
    }

    public function purchaseItems(): HasOne
    {
        return $this->hasOne(
            PurchaseItem::class,
            'financial_account_id',
            'id'
        );
    }

    public function employeePayments(): HasOne
    {
        return $this->hasOne(
            EmployeePayment::class,
            'financial_account_id',
            'id'
        );
    }
}
