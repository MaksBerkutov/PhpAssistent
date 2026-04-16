<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('public_api_enabled')->default(false)->after('role');
        });

        Schema::table('personal_access_tokens', function (Blueprint $table) {
            $table->boolean('is_public_api')->default(false)->after('expires_at');
            $table->boolean('is_enabled')->default(true)->after('is_public_api');
        });
    }

    public function down(): void
    {
        Schema::table('personal_access_tokens', function (Blueprint $table) {
            $table->dropColumn(['is_public_api', 'is_enabled']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('public_api_enabled');
        });
    }
};
