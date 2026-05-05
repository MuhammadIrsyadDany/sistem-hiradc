<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('live_audits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')->constrained('users');
            $table->string('diminta_oleh')->nullable();
            $table->text('nama_pekerjaan');
            $table->string('no_work_order')->nullable();
            $table->string('lokasi');
            $table->string('perusahaan');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->text('unsafe_action_text')->nullable();
            $table->text('unsafe_condition_text')->nullable();
            $table->json('working_permit')->nullable();
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
            $table->boolean('is_stopped')->default(false);
            $table->text('stop_alasan')->nullable();
            $table->timestamp('stopped_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('live_audits');
    }
};