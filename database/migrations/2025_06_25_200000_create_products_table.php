<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->string('stock_status', 50)->nullable();
            $table->string('image', 255)->nullable();
            $table->string('brand', 100)->nullable(); // Новый столбец

            $table->foreign('category_id')->references('id')->on('device_categories');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
        });
        Schema::dropIfExists('products');
    }
};
