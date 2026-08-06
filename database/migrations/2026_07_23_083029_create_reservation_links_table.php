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
        Schema::create('reservation_links', function (Blueprint $table) {
            $table->id();
            $table->text('link');
            $table->timestamps();
        });

        DB::table('reservation_links')->insert([
            'link' => 'https://forms.office.com/pages/responsepage.aspx?id=D_6vkKPCCEG7mGzrTpTvFX8cu6Jzq2tJlX2QoRxK9bJUMFlTUkJFWlcxSjRFQ0RGSFhIVUM0Wk4zSC4u&route=shorturl',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservation_links');
    }
};
