<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('temuans', function (Blueprint $table) {
            $table->foreignId('live_audit_id')
                ->nullable()
                ->after('id')
                ->constrained('live_audits');
        });
    }

    public function down(): void
    {
        Schema::table('temuans', function (Blueprint $table) {
            $table->dropForeign(['live_audit_id']);
            $table->dropColumn('live_audit_id');
        });
    }
};
