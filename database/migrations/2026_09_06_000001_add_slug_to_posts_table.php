<?php

use App\Support\Slug;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->string('slug')->after('title')->nullable();
        });

        $titles = DB::table('posts')->pluck('title', 'id');

        foreach ($titles as $id => $title) {
            $slug = Slug::makeUnique(Str::slug($title) ?: 'post', function (string $slug) use ($id) {
                return DB::table('posts')->where('slug', $slug)->where('id', '!=', $id)->exists();
            });

            DB::table('posts')->where('id', $id)->update(['slug' => $slug]);
        }

        Schema::table('posts', function (Blueprint $table) {
            $table->string('slug')->nullable(false)->unique()->change();
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
