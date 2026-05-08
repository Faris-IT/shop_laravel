<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->string('payment_method'); // bank_transfer, ewallet, cash
            $table->string('payment_provider')->nullable(); // bca, mandiri, bri, gopay, ovo, dana
            $table->string('proof_of_payment')->nullable(); // path file bukti transfer
            $table->decimal('amount', 12, 2);
            $table->enum('status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->timestamp('payment_date')->nullable();
            $table->text('notes')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
