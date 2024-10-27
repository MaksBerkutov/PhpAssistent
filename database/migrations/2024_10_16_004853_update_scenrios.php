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
        Schema::table('scenarios', function (Blueprint $table) {
            $table->dropForeign(['scenario_logs_id']);
            $table->dropForeign(['scenario_apis_id']);
            $table->dropForeign(['scenario_dbs_id']);
            $table->dropForeign(['scenario_notifies_id']);
            $table->dropForeign(['scenario_modules_id']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('scenarios', function (Blueprint $table) {
            $table->dropForeign(['scenario_logs_id']);
            $table->dropForeign(['scenario_apis_id']);
            $table->dropForeign(['scenario_dbs_id']);
            $table->dropForeign(['scenario_notifies_id']);
            $table->dropForeign(['scenario_modules_id']);
            $table->foreign('scenario_logs_id')->references('id')->on('scenario_logs')->onDelete('cascade');
            $table->foreign('scenario_apis_id')->references('id')->on('scenario_apis')->onDelete('cascade');
            $table->foreign('scenario_dbs_id')->references('id')->on('scenario_dbs')->onDelete('cascade');
            $table->foreign('scenario_notifies_id')->references('id')->on('scenario_notifies')->onDelete('cascade');
            $table->foreign('scenario_modules_id')->references('id')->on('scenario_modules')->onDelete('cascade');
        });
    }
};
