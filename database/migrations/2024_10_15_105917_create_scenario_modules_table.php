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
        Schema::create('scenario_modules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('devices_id');
            $table->string('command');
            $table->timestamps();
            $table->foreign('devices_id')->references('id')->on('devices')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scenario_modules');
    }
};
