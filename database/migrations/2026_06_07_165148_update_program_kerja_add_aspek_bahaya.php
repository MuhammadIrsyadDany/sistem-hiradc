<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('program_kerja', function (Blueprint $table) {
            $table->foreignId('aspek_bahaya_id')
                ->nullable()
                ->after('hiradc_id')
                ->constrained('hiradc_aspek_bahaya')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('program_kerja', function (Blueprint $table) {
            $table->dropForeign(['aspek_bahaya_id']);
            $table->dropColumn('aspek_bahaya_id');
        });
    }
};
