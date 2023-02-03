<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::connection('wallet')->create('journal_transaction_log', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique();

            $table->foreignId('transaction_id')->constrained('journal_transaction');
            $table->json('request');
            $table->json('response');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::connection('wallet')->dropIfExists('journal_transaction_log');
    }
};
