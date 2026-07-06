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
        Schema::create('password_recoveries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->text('first_question');
            $table->text('second_question');
            $table->text('first_answer');
            $table->text('second_answer');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('password_recoveries');
    }
};
