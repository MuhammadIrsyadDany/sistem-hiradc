<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hiradc_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('uploaded_by')->constrained('users');
            $table->string('judul');
            $table->string('unit')->nullable();
            $table->string('divisi')->nullable();
            $table->string('area_lokasi')->nullable();
            $table->string('penanggung_jawab')->nullable();
            $table->string('file_path');
            $table->enum('status', [
                'draft',
                'pending_v1',
                'pending_v2',
                'approved',
                'rejected'
            ])->default('draft');
            $table->foreignId('validated_by_v1')
                ->nullable()
                ->constrained('users');
            $table->foreignId('validated_by_v2')
                ->nullable()
                ->constrained('users');
            $table->timestamp('validated_at_v1')->nullable();
            $table->timestamp('validated_at_v2')->nullable();
            $table->text('catatan_penolakan')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hiradc_documents');
    }
};