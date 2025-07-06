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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->string('username', 100)->unique();
            $table->string('email', 150)->unique();
            $table->string('phone', 30)->nullable();
            $table->string('password', 255);
            $table->enum('role', ['user', 'admin'])->default('user');
            $table->dateTime('last_login')->nullable();
            $table->string('first_name', 100)->nullable();
            $table->string('last_name', 100)->nullable();
            $table->date('birthday')->nullable();
            $table->string('avatar', 255)->nullable();
            $table->timestamps();
            $table->foreign('employee_id')->references('id')->on('employees');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['employee_id']);
        });
        Schema::dropIfExists('users');
    }
};
