<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::connection('wallet')->create('transaction_type', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique();
            $table->integer('code')->unique();

            $table->string('name');
            $table->string('description');
            $table->boolean('status')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::connection('wallet')->dropIfExists('transaction_type');
    }
};
