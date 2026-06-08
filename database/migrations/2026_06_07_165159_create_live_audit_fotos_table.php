<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('live_audit_fotos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('live_audit_id')
                ->constrained('live_audits')
                ->onDelete('cascade');
            $table->string('foto_path');
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('live_audit_fotos');
    }
};
