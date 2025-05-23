<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guest_id')->constrained('guests')->onDelete('cascade');
            $table->foreignId('room_id')->constrained('rooms')->onDelete('cascade');
            $table->string('number')->nullable();
            $table->date('check_in_date');
            $table->date('check_out_date');
            $table->decimal('price', 16, 2)->nullable();
            $table->decimal('amount', 16, 2)->nullable();
            $table->decimal('down_payment', 16, 2)->nullable();
            $table->enum('down_payment_status', ['Pending', 'Paid'])->default('Pending');
            $table->enum('down_payment_method', ['Cash', 'Bank Transfer', 'Credit Card', 'E-Wallet'])->nullable();
            $table->string('down_payment_bank_name')->nullable();
            $table->string('down_payment_account_number')->nullable();
            $table->string('down_payment_transaction_id')->nullable();
            $table->date('down_payment_date')->nullable();
            $table->enum('payment_status', ['Pending', 'Partial Paid', 'Paid'])->default('Pending');
            $table->enum('payment_method', ['Cash', 'Bank Transfer', 'Credit Card', 'E-Wallet'])->nullable();
            $table->string('bank_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('card_holder_name')->nullable();
            $table->string('transaction_id')->nullable();
            $table->string('e_wallet_provider')->nullable();
            $table->enum('room_check_in', ['Off', 'On'])->default('Off');
            $table->enum('room_check_out', ['Off', 'On'])->default('Off');
            $table->enum('status', ['Pending', 'Confirmed', 'Canceled'])->default('Pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
