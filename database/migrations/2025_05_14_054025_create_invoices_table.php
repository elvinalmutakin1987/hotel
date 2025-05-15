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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('goodreceipt_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('supplier_id')->constrained()->onDelete('cascade');
            $table->string('number')->nullable();
            $table->string('supplier_bill')->nullable();
            $table->date('date')->nullable();
            $table->date('due_date')->nullable();
            $table->decimal('total', 16, 2)->nullable();
            $table->decimal('discount', 16, 2)->nullable();
            $table->decimal('after_discount', 16, 2)->nullable();
            $table->decimal('tax', 16, 2)->nullable();
            $table->decimal('grand_total', 16, 2)->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
