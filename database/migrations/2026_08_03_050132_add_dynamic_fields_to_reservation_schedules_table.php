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
        Schema::table('reservation_schedules', function (Blueprint $table) {
            $table->string('meeting_room')->nullable();
            $table->string('study_program')->nullable();
            $table->string('participants')->nullable();
            $table->text('agenda')->nullable();
            $table->string('city')->nullable();
            $table->string('prodi_signature_name')->nullable();
            $table->string('prodi_signature_position')->nullable();
            $table->string('related_party_signature_name')->nullable();
            $table->string('related_party_signature_position')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservation_schedules', function (Blueprint $table) {
            $table->dropColumn([
                'meeting_room',
                'study_program',
                'participants',
                'agenda',
                'city',
                'prodi_signature_name',
                'prodi_signature_position',
                'related_party_signature_name',
                'related_party_signature_position'
            ]);
        });
    }
};
