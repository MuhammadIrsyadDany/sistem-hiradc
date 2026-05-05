<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('program_kerja', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hiradc_id')->constrained('hiradc_documents');
            $table->foreignId('created_by')->constrained('users');
            $table->string('nama_program');
            $table->text('pengendalian_risiko')->nullable();
            $table->string('pic');
            $table->date('deadline');
            $table->enum('status', [
                'open',
                'on_progress',
                'overdue',
                'closed'
            ])->default('open');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('program_kerja');
    }
};