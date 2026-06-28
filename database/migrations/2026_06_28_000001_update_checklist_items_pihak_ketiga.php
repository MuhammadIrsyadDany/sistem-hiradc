<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\ChecklistItem;

return new class extends Migration
{
    public function up(): void
    {
        // Item 10
        ChecklistItem::where('nomor_item', 10)->update([
            'deskripsi' => 'Menyediakan penerangan di lokasi kerja yang memadai'
        ]);
        // Item 31
        ChecklistItem::where('nomor_item', 31)->update([
            'deskripsi' => 'Menyediakan penerangan yang cukup'
        ]);
    }

    public function down(): void
    {
        ChecklistItem::where('nomor_item', 10)->update([
            'deskripsi' => 'Pihak ketiga menyediakan penerangan di lokasi kerja yang memadai'
        ]);
        ChecklistItem::where('nomor_item', 31)->update([
            'deskripsi' => 'Pihak ketiga menyediakan penerangan yang cukup'
        ]);
    }
};
