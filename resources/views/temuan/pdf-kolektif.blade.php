<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .header-table td {
            padding: 5px;
        }

        h3 {
            text-align: center;
            font-size: 14px;
            margin: 5px 0;
        }

        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        table.data-table th,
        table.data-table td {
            border: 1px solid #000;
            padding: 6px;
            vertical-align: top;
        }

        table.data-table th {
            background: #f0f0f0;
            text-align: center;
            font-weight: bold;
        }

        .text-center {
            text-align: center;
        }

        .footer-table td {
            padding: 8px;
            vertical-align: top;
        }

        .filter-info {
            margin-bottom: 10px;
            font-size: 11px;
        }
    </style>
</head>

<body>
    {{-- Header --}}
    <table class="header-table" style="border: 1px solid #000;">
        <tr>
            <td width="15%" style="border-right: 1px solid #000; text-align: center;">
                <strong>PLN<br>Nusantara Power</strong>
            </td>
            <td width="55%" style="text-align:center; border-right: 1px solid #000;">
                <strong>PT. PLN NUSANTARA POWER</strong><br>
                INTEGRATED MANAGEMENT SYSTEM<br>
                <strong>LAPORAN KOLEKTIF TEMUAN K3</strong>
            </td>
            <td width="30%">
                Bulan: {{ $namaBulan }} {{ $tahun }}<br>
                Kategori: {{ $kategoriLabel }}<br>
                Status: {{ $statusLabel }}
            </td>
        </tr>
    </table>

    <div class="filter-info">
        Total Temuan: <strong>{{ $temuans->count() }}</strong>
    </div>

    {{-- Data Tabel --}}
    <table class="data-table">
        <thead>
            <tr>
                <th width="4%">No</th>
                <th width="20%">Judul Temuan</th>
                <th width="12%">Lokasi</th>
                <th width="10%">Kategori</th>
                <th width="12%">Dilaporkan Oleh</th>
                <th width="10%">PIC</th>
                <th width="18%">Tindak Lanjut / Rekomendasi</th>
                <th width="8%">Status</th>
                <th width="6%">Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($temuans as $index => $temuan)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td><strong>{{ $temuan->judul_temuan }}</strong></td>
                    <td>
                        {{ $temuan->lokasi }}
                        @if ($temuan->keterangan_lokasi)
                            <br><small style="color: #666;">({{ $temuan->keterangan_lokasi }})</small>
                        @endif
                    </td>
                    <td class="text-center">
                        @if ($temuan->kategori === 'unsafe_action')
                            Unsafe Action
                        @elseif ($temuan->kategori === 'unsafe_condition')
                            Unsafe Condition
                        @elseif ($temuan->kategori === 'near_miss')
                            Near Miss
                        @elseif ($temuan->kategori === 'positive')
                            Positive
                        @else
                            {{ $temuan->kategori }}
                        @endif
                    </td>
                    <td>{{ $temuan->reporter->name }}</td>
                    <td>{{ $temuan->pic ?? '-' }}</td>
                    <td>
                        @if ($temuan->tindak_lanjut)
                            <strong>Tindak Lanjut:</strong> {{ $temuan->tindak_lanjut }}<br>
                        @endif
                        @if ($temuan->rekomendasi)
                            <strong>Rekomendasi:</strong> {{ $temuan->rekomendasi }}
                        @endif
                        @if (!$temuan->tindak_lanjut && !$temuan->rekomendasi)
                            -
                        @endif
                    </td>
                    <td class="text-center">
                        @if ($temuan->status === 'draft')
                            Draft
                        @elseif ($temuan->status === 'open')
                            Open
                        @elseif ($temuan->status === 'validated_v1')
                            Validated V1
                        @elseif ($temuan->status === 'validated_v2')
                            Validated V2
                        @elseif ($temuan->status === 'closed')
                            Closed
                        @else
                            {{ $temuan->status }}
                        @endif
                    </td>
                    <td class="text-center">{{ $temuan->created_at->format('d/m/Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center" style="padding: 20px; color: #666;">
                        Tidak ada data temuan untuk periode dan filter yang dipilih.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
