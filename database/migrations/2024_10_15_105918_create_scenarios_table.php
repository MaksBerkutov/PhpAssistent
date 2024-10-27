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
        Schema::create('scenarios', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('devices_id');
            $table->unsignedBigInteger('users_id');
            $table->string('key');
            $table->string('value');
            $table->unsignedBigInteger('scenario_logs_id')->nullable();
            $table->unsignedBigInteger('scenario_apis_id')->nullable();
            $table->unsignedBigInteger('scenario_dbs_id')->nullable();
            $table->unsignedBigInteger('scenario_notifies_id')->nullable();
            $table->unsignedBigInteger('scenario_modules_id')->nullable();
            $table->timestamps();
            $table->foreign('devices_id')->references('id')->on('devices')->onDelete('cascade');
            $table->foreign('users_id')->references('id')->on('users')->onDelete('cascade');

            $table->foreign('scenario_logs_id')->references('id')->on('scenario_logs')->onDelete('cascade');
            $table->foreign('scenario_apis_id')->references('id')->on('scenario_apis')->onDelete('cascade');
            $table->foreign('scenario_dbs_id')->references('id')->on('scenario_dbs')->onDelete('cascade');
            $table->foreign('scenario_notifies_id')->references('id')->on('scenario_notifies')->onDelete('cascade');
            $table->foreign('scenario_modules_id')->references('id')->on('scenario_modules')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scenarios');
    }
};
