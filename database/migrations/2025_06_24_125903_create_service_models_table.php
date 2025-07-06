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
        Schema::create('services', function (Blueprint $table) {
            $table->id(); // Эквивалент INT AUTO_INCREMENT PRIMARY KEY
            $table->string('name', 100)->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->integer('duration_minutes')->nullable();
            $table->string('image')->nullable(); // <--- добавлено
            // Внешний ключ
            $table->foreign('category_id')->references('id')->on('service_categories');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            // Удаление внешнего ключа перед удалением таблицы
            $table->dropForeign(['category_id']);
        });
        Schema::dropIfExists('services');
    }
};

