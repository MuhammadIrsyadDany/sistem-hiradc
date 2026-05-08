<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE temuans MODIFY COLUMN status ENUM(
            'draft',
            'open',
            'validated_v1',
            'validated_v2',
            'closed'
        ) NOT NULL DEFAULT 'open'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE temuans MODIFY COLUMN status ENUM(
            'open',
            'validated_v1',
            'validated_v2',
            'closed'
        ) NOT NULL DEFAULT 'open'");
    }
};
