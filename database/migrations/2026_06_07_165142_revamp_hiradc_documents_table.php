<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hiradc_documents', function (Blueprint $table) {
            // Drop foreign key constraints dulu sebelum drop kolom
            $table->dropForeign(['validated_by_v1']);
            $table->dropForeign(['validated_by_v2']);

            // Baru hapus kolom
            $table->dropColumn([
                'status',
                'validated_by_v1',
                'validated_by_v2',
                'validated_at_v1',
                'validated_at_v2',
                'catatan_penolakan',
            ]);

            // Rename judul → nama_area
            $table->renameColumn('judul', 'nama_area');

            // Tambah kolom baru
            $table->string('tahun')->nullable()->after('nama_area');
            $table->string('no_dokumen')->nullable()->after('tahun');
        });
    }

    public function down(): void
    {
        Schema::table('hiradc_documents', function (Blueprint $table) {
            $table->renameColumn('nama_area', 'judul');
            $table->dropColumn(['tahun', 'no_dokumen']);

            $table->enum('status', [
                'draft',
                'pending_v1',
                'pending_v2',
                'approved',
                'rejected',
            ])->default('draft');

            $table->unsignedBigInteger('validated_by_v1')->nullable();
            $table->unsignedBigInteger('validated_by_v2')->nullable();
            $table->timestamp('validated_at_v1')->nullable();
            $table->timestamp('validated_at_v2')->nullable();
            $table->text('catatan_penolakan')->nullable();

            $table->foreign('validated_by_v1')
                ->references('id')->on('users');
            $table->foreign('validated_by_v2')
                ->references('id')->on('users');
        });
    }
};
