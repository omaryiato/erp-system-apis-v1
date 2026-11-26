<?php

declare(strict_types=1);

use App\Models\Inventory\CashTransaction;
use App\Models\Inventory\Expense;
use App\Models\Inventory\Project;
use App\Models\Inventory\Purchase;
use App\Models\Inventory\Revenue;
use App\Models\Inventory\Supplier;
use App\Repositories\Inventory\ReportRepository;
use App\Services\Inventory\CashTransactionService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;

test('cash payments stay scoped to their type when references share an ID', function () {
    config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);

    foreach (['expenses_v1', 'revenues_v1', 'purchases_v1'] as $tableName) {
        Schema::create($tableName, function (Blueprint $table): void {
            $table->id();
            $table->decimal('amount', 15, 2);
            $table->unsignedBigInteger('project_id')->nullable();
            $table->unsignedBigInteger('supplier_id')->nullable();
        });
    }

    Schema::create('cash_transactions_v1', function (Blueprint $table): void {
        $table->id();
        $table->string('transaction_type');
        $table->unsignedBigInteger('reference_id')->nullable();
        $table->unsignedBigInteger('employee_payment_id')->nullable();
        $table->decimal('amount', 15, 2);
        $table->date('transaction_date');
        $table->string('payment_method');
        $table->unsignedBigInteger('created_by')->nullable();
        $table->unsignedBigInteger('updated_by')->nullable();
        $table->timestamps();
    });

    DB::table('expenses_v1')->insert(['id' => 7, 'amount' => 50]);
    DB::table('revenues_v1')->insert(['id' => 7, 'amount' => 80, 'project_id' => 3]);
    DB::table('purchases_v1')->insert(['id' => 7, 'amount' => 100, 'supplier_id' => 4]);

    $service = app(CashTransactionService::class);
    $base = ['reference_id' => 7, 'transaction_date' => '2026-11-26', 'payment_method' => 'cash'];
    $expense = $service->create($base + ['transaction_type' => 'expense', 'amount' => 45], 1);
    $service->create($base + ['transaction_type' => 'revenue', 'amount' => 60], 1);
    $service->create($base + ['transaction_type' => 'purchase', 'amount' => 70], 1);
    $service->update($expense, $base + ['transaction_type' => 'expense', 'amount' => 50], 2);

    expect(CashTransaction::count())->toBe(3);
    expect($expense->fresh()->created_by)->toBe(1);
    expect($expense->fresh()->updated_by)->toBe(2);
    expect((float) Expense::findOrFail(7)->cashTransactions()->sum('amount'))->toBe(50.0);
    expect((float) Revenue::findOrFail(7)->cashTransactions()->sum('amount'))->toBe(60.0);
    expect((float) Purchase::findOrFail(7)->cashTransactions()->sum('amount'))->toBe(70.0);
    expect((float) (new Project)->forceFill(['id' => 3])->cashTransactions()->sum('cash_transactions_v1.amount'))->toBe(60.0);
    expect((float) (new Supplier)->forceFill(['id' => 4])->cashTransactions()->sum('cash_transactions_v1.amount'))->toBe(70.0);
    expect(app(ReportRepository::class)->cashFlow([]))->toMatchArray(['total_income' => 60.0, 'total_expense' => 120.0]);
    expect(app(ReportRepository::class)->cashTransactions(['supplier_id' => 4])->total())->toBe(1);
    expect(app(ReportRepository::class)->cashTransactions(['project_id' => 3])->total())->toBe(1);
    expect(fn () => $service->create($base + ['transaction_type' => 'expense', 'amount' => 1], 1))
        ->toThrow(ValidationException::class);
});
