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
        Schema::create('employees', function (Blueprint $table) {
            $table->id(); // Эквивалент INT AUTO_INCREMENT PRIMARY KEY
            $table->string('full_name', 255)->nullable();
            $table->string('phone', 50)->nullable();
            $table->string('email', 100)->nullable();
            $table->unsignedBigInteger('position_id')->nullable();
            $table->dateTime('hire_date')->nullable();
            $table->boolean('is_active')->default(true);
        
            // Внешний ключ
            $table->foreign('position_id')->references('id')->on('positions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            // Удаление внешнего ключа перед удалением таблицы
            $table->dropForeign(['position_id']);
        });
        Schema::dropIfExists('employees');
    }
};
