<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nip')->nullable()->after('name');
            $table->string('jabatan')->nullable()->after('nip');
            $table->string('no_hp')->nullable()->after('jabatan');
            $table->boolean('is_active')->default(true)->after('no_hp');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['nip', 'jabatan', 'no_hp', 'is_active']);
        });
    }
};