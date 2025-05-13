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
        Schema::create('goodreceipts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('supplier_id')->constrained()->onDelete('cascade');
            $table->string('number')->nullable();
            $table->date('date')->nullable();
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
        Schema::dropIfExists('goodreceipts');
    }
};
