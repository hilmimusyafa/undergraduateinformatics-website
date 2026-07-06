<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('feedback_links', function (Blueprint $table) {
            $table->id();
            $table->text('link');
            $table->timestamps();
        });

        // Insert the constant record
        DB::table('feedback_links')->insert([
            'link' => 'https://forms.office.com/pages/responsepage.aspx?id=D_6vkKPCCEG7mGzrTpTvFc9ujqZdH91MtXpfw-rWy2hUNFA5NUhUMlYwNU5RSE5TVDlWUzI1WUZTRi4u',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedback_links');
    }
};
