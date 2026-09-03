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
        Schema::table('tags', function (Blueprint $table) {
            $table->string('slug')->after('name')->nullable();
        });

        $names = DB::table('tags')->pluck('name', 'id');

        foreach ($names as $id => $name) {
            $slug = Slug::makeUnique(Str::slug($name) ?: 'tag', function (string $slug) use ($id) {
                return DB::table('tags')->where('slug', $slug)->where('id', '!=', $id)->exists();
            });

            DB::table('tags')->where('id', $id)->update(['slug' => $slug]);
        }

        Schema::table('tags', function (Blueprint $table) {
            $table->string('slug')->nullable(false)->unique()->change();
        });
    }

    public function down(): void
    {
        Schema::table('tags', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
