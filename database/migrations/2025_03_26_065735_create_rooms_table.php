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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('number')->nullable();
            $table->unsignedBigInteger('roomtype_id');
            $table->enum('status', ['Available', 'Occupied', 'Reserved', 'Housekeeping', 'Maintenance', 'Out of Order', 'Blocked', 'Check-in', 'Check-out'])->default('Available');
            $table->foreign('roomtype_id')->references('id')->on('roomtypes')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
