<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('reference_no')->unique();
            $table->string('name');
            $table->string('address');
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->enum('payment_method', ['CASH', 'TRANSFER', 'QRIS', 'COD'])->default('cash');
            $table->enum('payment_status', ['PENDING', 'PAID', 'CANCELED', 'REFUNDED'])->default('pending');
            $table->text('notes')->nullable();
            $table->string('proof')->nullable();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('verifier_id')->nullable()->constrained('users');
            $table->timestamp('verified_at')->nullable()->constrained();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
