<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('app_settings', function (Blueprint $table) {
            $table->id();
            $table->string('app');   // slug приложения, например telegram-bot
            $table->string('key');   // ключ настройки, например api_token
            $table->text('value')->nullable(); // значение настройки
            $table->timestamps();

            $table->unique(['app', 'key']); // чтобы один ключ был уникален для каждого аппса
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('app_settings');
    }
};
