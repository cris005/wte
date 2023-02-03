<?php

use App\Models\User\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::connection('wallet')->create('transaction', function (Blueprint $table) {
            $dbName = DB::connection('app')->getDatabaseName();

            $table->id();
            $table->uuid()->unique();
            $table->string('ref_no')->unique();
            $table->foreignIdFor(User::class, 'user_id');

            // type_id -> DATA2
            $table->foreignId('type_id')->constrained('transaction_type');
            $table->foreignId('provider_id')->constrained('transaction_provider');
            $table->foreignId('channel_id')->constrained('transaction_channel');
            $table->foreignId('status_id')->constrained('transaction_status');
            $table->foreignId('error_id')->constrained('transaction_error');
            $table->foreignId('debit_account_no')->constrained($dbName . '.ACCOUNT', 'ACCOUNT_NO');
            $table->foreignId('credit_account_no')->constrained($dbName . '.ACCOUNT', 'ACCOUNT_NO');
            $table->decimal('amount', 19, 4, true);
            $table->foreignId('origin_currency_id')->constrained($dbName . '.global_currency');
            $table->foreignId('target_currency_id')->constrained($dbName . '.global_currency');

            // external_ref_no -> DATA3
            $table->string('external_ref_no')->nullable();
            // remarks -> DATA4
            $table->string('remarks')->nullable();
            // -> DATA5

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::connection('wallet')->dropIfExists('transaction');
    }
};
