<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Kedisiplinan - {{ $namaBulan }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #1a1a2e; font-size: 12px; }
        .header { text-align: center; border-bottom: 3px double #03045E; padding-bottom: 15px; margin-bottom: 20px; }
        .header h1 { font-size: 18px; color: #03045E; text-transform: uppercase; letter-spacing: 2px; }
        .header p { font-size: 12px; color: #666; margin-top: 4px; }
        .section-title { font-size: 14px; font-weight: bold; color: #03045E; margin: 20px 0 10px 0; padding-bottom: 5px; border-bottom: 2px solid #03045E; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th { background-color: #03045E; color: white; padding: 8px 10px; text-align: center; font-size: 11px; text-transform: uppercase; letter-spacing: 1px; }
        td { padding: 7px 10px; border-bottom: 1px solid #e0e0e0; text-align: center; }
        tr:nth-child(even) { background-color: #f8f9fa; }
        .text-left { text-align: left; }
        .badge { padding: 2px 8px; border-radius: 10px; font-size: 10px; font-weight: bold; }
        .badge-ringan { background: #dbeafe; color: #2563eb; }
        .badge-sedang { background: #fef3c7; color: #d97706; }
        .badge-berat { background: #fee2e2; color: #dc2626; }
        .footer { text-align: center; font-size: 10px; color: #999; margin-top: 30px; padding-top: 10px; border-top: 1px solid #ddd; }
        @media print {
            body { font-size: 11px; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>
    <div style="padding: 30px;">
        <!-- Tombol Cetak -->
        <div class="no-print" style="text-align: right; margin-bottom: 20px;">
            <button onclick="window.print()" style="background: #03045E; color: white; border: none; padding: 10px 24px; border-radius: 20px; font-weight: bold; cursor: pointer;">
                🖨️ Cetak / Simpan PDF
            </button>
        </div>

        <!-- Header -->
        <div class="header">
            <h1>Laporan Monitoring Kedisiplinan</h1>
            <p>Periode: {{ $namaBulan }}</p>
            <p>Dicetak pada: {{ now()->translatedFormat('l, d F Y H:i') }}</p>
        </div>

        <!-- Tabel Presensi per Kelas -->
        <div class="section-title">Rekapitulasi Kehadiran per Kelas</div>
        <table>
            <thead>
                <tr>
                    <th class="text-left">Kelas</th>
                    <th>Tingkat</th>
                    <th>Hadir</th>
                    <th>Sakit</th>
                    <th>Izin</th>
                    <th>Alpa</th>
                    <th>Dispen</th>
                    <th>Total</th>
                    <th>% Hadir</th>
                </tr>
            </thead>
            <tbody>
                @forelse($presensiPerKelas as $p)
                <tr>
                    <td class="text-left" style="font-weight: bold;">{{ $p->nama_kelas }}</td>
                    <td>{{ $p->tingkat }}</td>
                    <td>{{ $p->hadir }}</td>
                    <td>{{ $p->sakit }}</td>
                    <td>{{ $p->izin }}</td>
                    <td>{{ $p->alpa }}</td>
                    <td>{{ $p->dispen }}</td>
                    <td style="font-weight: bold;">{{ $p->total }}</td>
                    <td style="font-weight: bold;">{{ $p->total > 0 ? round(($p->hadir / $p->total) * 100, 1) : 0 }}%</td>
                </tr>
                @empty
                <tr><td colspan="9">Belum ada data presensi.</td></tr>
                @endforelse
            </tbody>
        </table>

        <!-- Tabel Catatan Pelanggaran -->
        <div class="section-title">Daftar Catatan Pelanggaran</div>
        <table>
            <thead>
                <tr>
                    <th class="text-left">Tanggal</th>
                    <th class="text-left">Siswa</th>
                    <th class="text-left">Pelanggaran</th>
                    <th>Kategori</th>
                    <th class="text-left">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pelanggaranList as $pl)
                <tr>
                    <td class="text-left">{{ \Carbon\Carbon::parse($pl->tanggal)->format('d/m/Y') }}</td>
                    <td class="text-left" style="font-weight: bold;">{{ $pl->siswa->nama_lengkap ?? $pl->siswa_nisn }}</td>
                    <td class="text-left">{{ $pl->pelanggaran->nama_pelanggaran ?? '-' }}</td>
                    <td>
                        @php $kat = $pl->pelanggaran->kategori ?? 'Ringan'; @endphp
                        <span class="badge badge-{{ strtolower($kat) }}">{{ $kat }}</span>
                    </td>
                    <td class="text-left">{{ $pl->keterangan ?? '-' }}</td>
                </tr>
                @empty
                <tr><td colspan="5">Tidak ada catatan pelanggaran pada periode ini.</td></tr>
                @endforelse
            </tbody>
        </table>

        <div class="footer">
            Dokumen ini digenerate otomatis oleh Sistem Monitoring EduConnect.
        </div>
    </div>
</body>
</html>
