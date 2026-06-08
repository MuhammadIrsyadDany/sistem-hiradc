<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hiradc_aspek_bahaya', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aktivitas_id')
                ->constrained('hiradc_aktivitas')
                ->onDelete('cascade');
            $table->text('potensi_aspek_lingkungan')->nullable();
            $table->text('potensi_bahaya_k3');
            $table->text('peraturan_terkait')->nullable();
            $table->text('pengendalian_existing')->nullable();
            $table->enum('level_risiko', [
                'rendah',
                'moderat',
                'tinggi',
                'sangat_tinggi',
                'ekstrim',
            ]);
            $table->enum('level_risiko_akhir', [
                'rendah',
                'moderat',
                'tinggi',
                'sangat_tinggi',
                'ekstrim',
            ])->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hiradc_aspek_bahaya');
    }
};
