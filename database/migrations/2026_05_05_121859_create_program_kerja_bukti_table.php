<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('program_kerja_bukti', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_kerja_id')
                ->constrained('program_kerja');
            $table->foreignId('uploaded_by')->constrained('users');
            $table->string('foto_path');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('program_kerja_bukti');
    }
};