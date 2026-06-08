<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hiradc_aktivitas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hiradc_id')
                ->constrained('hiradc_documents')
                ->onDelete('cascade');
            $table->string('nama_aktivitas');
            $table->enum('sumber_bahaya', [
                'aktivitas',
                'peralatan',
                'lingkungan_kerja',
                'proses',
            ]);
            $table->enum('kondisi', [
                'rutin',
                'non_rutin',
                'abnormal',
                'darurat',
            ])->default('rutin');
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hiradc_aktivitas');
    }
};
