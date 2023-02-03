<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::connection('wallet')->create('journal_transaction', function (Blueprint $table) {
            $dbName = DB::connection('app')->getDatabaseName();

            $table->id();
            $table->uuid()->unique();

            $table->foreignId('transaction_id')->constrained($dbName . '.transaction');
            $table->foreignId('type_id')->constrained('journal_transaction_type');
            $table->decimal('amount', 19, 4, true);
            $table->foreignId('currency_id')->constrained($dbName . '.global_currency');
            $table->foreignId('debit_account_no')->constrained($dbName . '.ACCOUNT', 'ACCOUNT_NO');
            $table->decimal('debit_beg_balance', 19, 4, false);
            $table->decimal('debit_end_balance', 19, 4, false);
            $table->foreignId('credit_account_no')->constrained($dbName . '.ACCOUNT', 'ACCOUNT_NO');
            $table->decimal('credit_beg_balance', 19, 4, false);
            $table->decimal('credit_end_balance', 19, 4, false);
            $table->foreignId('status_id')->constrained('journal_transaction_status');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::connection('wallet')->dropIfExists('journal_transaction');
    }
};
