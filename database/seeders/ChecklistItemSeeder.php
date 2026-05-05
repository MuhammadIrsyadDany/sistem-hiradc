<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ChecklistItem;

class ChecklistItemSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            // Section: Umum
            [1,  'Umum', 'Pekerjaan yang dilakukan sudah sesuai JSA', false],
            [2,  'Umum', 'Pengawas pekerjaan ada di tempat kerja', false],
            [3,  'Umum', 'Pengawas K3 ada di tempat kerja', true],
            [4,  'Umum', 'APD yang digunakan pekerja sesuai pekerjaan dan dalam kondisi layak', true],
            [5,  'Umum', 'Peralatan kerja yang digunakan sesuai pekerjaan dan sesuai SOP', false],
            [6,  'Umum', 'Kondisi pekerja dalam keadaan sehat dan siap kerja', false],
            [7,  'Umum', 'Terdapat permit sesuai jenis pekerjaan yang dilakukan dan masih berlaku termasuk permit khusus', true],
            [8,  'Umum', 'Terdapat izin kerja overtime', true],
            [9,  'Umum', 'Safety sign terpasang sesuai dan dalam keadaan baik', false],
            [10, 'Umum', 'Pihak ketiga menyediakan penerangan di lokasi kerja yang memadai', false],
            [11, 'Umum', 'Emergency tools tersedia dan dalam keadaan baik (seperti APAR, fire blanket dan peralatan P3K)', false],
            [12, 'Umum', 'Telah dilakukan sosialisasi prosedur keadaan darurat', false],
            [13, 'Umum', 'Kebersihan dan kerapian area kerja termasuk kerapian dan keamanan kabel listrik', false],
            [14, 'Umum', 'Tidak terdapat tumpahan atau ceceran LB3 di area kerja', false],
            [15, 'Umum', 'Sampah dan material bekas dikelola dengan baik', false],
            [16, 'Umum', 'Pengendalian bahaya material dilakukan sesuai MSDS', false],
            [17, 'Umum', 'Tidak terdapat pelanggaran merokok', true],
            [18, 'Umum', 'Tidak terdapat pelanggaran miras dan obat-obatan terlarang', true],
            [19, 'Umum', 'Operator yang sedang mengoperasikan peralatan kerja memiliki sertifikat kompetensi sesuai aturan yang berlaku', true],
            [20, 'Umum', 'Pekerjaan radiasi pengion sudah dikoordinasikan dan dilakukan lokalisir area dan tidak ada orang di area tersebut', false],
            [21, 'Umum', 'Tidak ada pelanggaran dari permit isolasi yang sudah dilakukan', true],
            [22, 'Umum', 'Tidak ada pelanggaran dari permit vicinity yang sudah dilakukan', true],
            [23, 'Umum', 'Tidak ada pelanggaran dari permit under water yang sudah dilakukan', true],
            // Section: Hotwork
            [24, 'Hotwork', 'Terdapat fire watch di area kerja dan jumlahnya sesuai kebutuhan', true],
            [25, 'Hotwork', 'Fire watch log terisi dan termonitor', false],
            [26, 'Hotwork', 'Penempatan tabung bertekanan harus dalam kondisi stabil dan aman', false],
            [27, 'Hotwork', 'Pengelasan dilakukan oleh welder yang bersertifikat', false],
            // Section: Confined Space
            [28, 'Confined Space', 'Terdapat standby person pada akses confined space dan melakukan pengawasan', true],
            [29, 'Confined Space', 'Terdapat catatan keluar masuk karyawan kedalam confined space', true],
            [30, 'Confined Space', 'Tersedia peralatan untuk sirkulasi udara yang sedang beroperasi (blower/exhaust fan)', false],
            [31, 'Confined Space', 'Pihak ketiga menyediakan penerangan yang cukup', false],
            // Section: Ketinggian
            [32, 'Ketinggian', 'Sudah dilakukan pengecekan perancah, scaffolding atau tangga telah diperiksa dan terdapat tagging', false],
            // Section: Penggalian
            [33, 'Penggalian', 'Dilakukan upaya mengantisipasi dampak kerusakan struktur galian', false],
            [34, 'Penggalian', 'Sudah dilakukan tindaklanjut terhadap temuan hasil pre job activity', false],
        ];

        foreach ($items as $index => $item) {
            ChecklistItem::firstOrCreate(
                ['nomor_item' => $item[0]],
                [
                    'section'     => $item[1],
                    'deskripsi'   => $item[2],
                    'is_critical' => $item[3],
                    'is_active'   => true,
                    'urutan'      => $index + 1,
                ]
            );
        }
    }
}