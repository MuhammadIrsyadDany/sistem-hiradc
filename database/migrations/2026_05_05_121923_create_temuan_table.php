<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('temuans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reported_by')->constrained('users');
            $table->string('distrik');
            $table->string('judul_temuan');
            $table->string('kondisi')->nullable();
            $table->text('tindak_lanjut')->nullable();
            $table->text('rekomendasi')->nullable();
            $table->string('pic')->nullable();
            $table->string('lokasi')->nullable();
            $table->string('keterangan_lokasi')->nullable();
            $table->enum('kategori', [
                'unsafe_action',
                'unsafe_condition',
                'near_miss',
                'positive'
            ]);
            $table->string('ai_kategori')->nullable();
            $table->float('ai_confidence')->nullable();
            $table->enum('status', [
                'open',
                'validated_v1',
                'validated_v2',
                'closed'
            ])->default('open');
            $table->foreignId('validated_by_v1')
                ->nullable()
                ->constrained('users');
            $table->foreignId('validated_by_v2')
                ->nullable()
                ->constrained('users');
            $table->foreignId('closed_by')
                ->nullable()
                ->constrained('users');
            $table->timestamp('validated_at_v1')->nullable();
            $table->timestamp('validated_at_v2')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('temuans');
    }
};