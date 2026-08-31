<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $link = config('feedback.form_link');
        $existing = DB::table('feedback_links')->first();

        if ($existing !== null) {
            DB::table('feedback_links')->where('id', $existing->id)->update([
                'link' => $link,
                'updated_at' => now(),
            ]);

            return;
        }

        DB::table('feedback_links')->insert([
            'link' => $link,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
    }
};