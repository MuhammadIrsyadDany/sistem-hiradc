<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('live_audit_checklists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('live_audit_id')->constrained('live_audits');
            $table->foreignId('checklist_item_id')
                ->constrained('checklist_items');
            $table->enum('jawaban', ['tidak', 'ya', 'na'])->default('na');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('live_audit_checklists');
    }
};