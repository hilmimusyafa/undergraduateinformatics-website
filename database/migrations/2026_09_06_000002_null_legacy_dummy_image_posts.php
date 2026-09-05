<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('posts')
            ->where('image', 'images/DummyImage.png')
            ->update(['image' => null]);
    }

    public function down(): void
    {
        DB::table('posts')
            ->whereNull('image')
            ->update(['image' => 'images/DummyImage.png']);
    }
};