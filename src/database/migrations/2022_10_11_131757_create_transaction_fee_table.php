<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::connection('wallet')->create('transaction_fee', function (Blueprint $table) {
            $dbName = DB::connection('app')->getDatabaseName();

            $table->id();
            $table->uuid();

            $table->foreignId('transaction_id')->constrained('transaction');
            $table->foreignId('type_id')->constrained('transaction_fee_type');
            $table->decimal('amount', 19, 4, true);
            $table->foreignId('account_no')->constrained($dbName . '.ACCOUNT', 'ACCOUNT_NO');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::connection('wallet')->dropIfExists('transaction_fee');
    }
};
