<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::connection('wallet')->create('transaction_category', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique();

            $table->foreignId('type_id')->constrained('transaction_type');
            $table->string('name');
            $table->string('description');
            $table->boolean('status')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::connection('wallet')->dropIfExists('transaction_category');
    }
};
