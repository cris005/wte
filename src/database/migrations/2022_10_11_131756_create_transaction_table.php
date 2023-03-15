<?php

use App\Models\Config\Currency;
use App\Models\User\User;
use App\Models\User\Wallet;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::connection('wallet')->create('transaction', function (Blueprint $table) {
            $dbApp = DB::connection('app')->getDatabaseName();
            $dbUsers = DB::connection('users')->getDatabaseName();

            $table->id();
            $table->uuid()->unique();
            $table->string('ref_no')->unique();
            $table->foreignId('user_id')->constrained($dbUsers . '.users');

            // type_id -> DATA2
            $table->foreignId('category_id')->constrained('transaction_category');
            //$table->foreignId('provider_id')->constrained('transaction_provider');
            $table->foreignId('channel_id')->constrained('transaction_channel');
            $table->foreignId('status_id')->constrained('transaction_status');
            $table->foreignId('error_id')->constrained('transaction_error');
            $table->foreignId('debit_account_id')->constrained($dbApp . '.WACCOUNTS', 'ACCOUNT_ID');
            $table->foreignId('credit_account_id')->constrained($dbApp . '.WACCOUNTS', 'ACCOUNT_ID');
            $table->decimal('amount', 19, 4, true);
            $table->foreignId('origin_currency_id')->constrained($dbApp . '.global_currency', 'iso_number');
            $table->foreignId('target_currency_id')->constrained($dbApp . '.global_currency', 'iso_number');

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
