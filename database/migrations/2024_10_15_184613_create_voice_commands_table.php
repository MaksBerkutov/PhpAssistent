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
        Schema::create('voice_commands', function (Blueprint $table) {
            $table->id();
            $table->string('text_trigger');
            $table->string('command');
            $table->string('voice')->nullable();
            $table->unsignedBigInteger('devices_id');
            $table->unsignedBigInteger('users_id');
            $table->timestamps();
            $table->foreign('devices_id')->references('id')->on('devices')->onDelete('cascade');
            $table->foreign('users_id')->references('id')->on('users')->onDelete('cascade');

        });
    }

    /**
     * 'devices_id',
     * 'command',
     * 'text_trigger'
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('voice_commands');
    }
};
