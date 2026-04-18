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
        Schema::table('articles', function (Blueprint $table) {
            // Добавляем поле для картинки (nullable — картинка не обязательна)
            $table->string('image')->nullable()->after('content'); 
            
            // Добавляем поле для блокировки (по умолчанию статья НЕ заблокирована)
            $table->boolean('is_blocked')->default(false)->after('price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            // Откатываем изменения, если потребуется сделать migrate:rollback
            $table->dropColumn(['image', 'is_blocked']);
        });
    }
};