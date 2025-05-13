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
        Schema::create('goodreceiptdetails', function (Blueprint $table) {
            $table->id();
            $table->foreignId('goodreceipt_id')->constrained()->onDelete('cascade');
            $table->foreignId('item_id')->constrained()->onDelete('cascade');
            $table->string('unit')->nullable();
            $table->decimal('price', 16, 2)->nullable();
            $table->decimal('discount', 16, 2)->nullable();
            $table->decimal('after_discount', 16, 2)->nullable();
            $table->decimal('tax', 16, 2)->nullable();
            $table->decimal('qty', 16, 2)->nullable();
            $table->decimal('sub_total')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goodreceiptdetails');
    }
};
