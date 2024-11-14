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
        Schema::table('scenario_modules', function (Blueprint $table) {
            $table->string('arg')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('scenario_modules', function (Blueprint $table) {
            $table->dropColumn('arg');
        });
    }
};
