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
        Schema::create('scenario_dbs', function (Blueprint $table) {
            $table->id();
            $table->string('login');
            $table->string('password');
            $table->string('db_name');
            $table->string('table_name');
            $table->string('name_key')->nullable();
            $table->string('name_value')->nullable();
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scenario_dbs');
    }
};
