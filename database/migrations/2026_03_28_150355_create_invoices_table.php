<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('course_id')->nullable()->constrained('courses')->nullOnDelete();
            $table->foreignId('coupon_id')->nullable()->constrained('coupon')->nullOnDelete();
            $table->string('payment_description')->default('Cobrança');
            $table->decimal('payment_value', 10, 2);
            $table->enum('payment_status', ['pending', 'paid', 'canceled'])->default('pending');
            $table->enum('payment_method', ['PIX', 'BOLETO', 'CREDIT_CARD', 'OUTRO'])->default('PIX');
            $table->enum('payment_type', ['revenue', 'expense'])->default('revenue');
            $table->string('payment_token')->nullable();
            $table->string('payment_url')->nullable();
            $table->dateTime('payment_due_date')->nullable();
            $table->dateTime('payment_paid_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void {
        Schema::dropIfExists('invoices');
    }
};
