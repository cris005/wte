<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::connection('wallet')->create('transaction_meta', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique();

            $table->foreignId('transaction_id')->constrained('transaction');
            $table->string('key');
            $table->longText('value');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::connection('wallet')->dropIfExists('transaction_meta');
    }
};
