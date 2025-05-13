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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('item_code')->nullable();
            $table->string('name')->nullable();
            $table->enum('unit', [
                'Pieces',
                'Box',
                'Pack',
                'Dozen',
                'Set',
                'Unit',
                'Milliliter',
                'Liter',
                'Gallon',
                'Bottle',
                'Gram',
                'Kilogram',
                'Ounce',
                'Ton',
                'Meter',
                'Centimeter',
                'Roll',
                'Sheet',
                'Day',
                'Night',
                'Hour',
                'Service'
            ])->nullable();
            $table->decimal('stock', 16, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
