<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Akademik - {{ $semester }} {{ $tahunAjaran }}</title>
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
        .highlight { font-weight: bold; color: #03045E; }
        .good { color: #059669; }
        .warn { color: #d97706; }
        .bad { color: #dc2626; }
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
            <h1>Laporan Monitoring Akademik</h1>
            <p>Semester: {{ $semester }} | Tahun Ajaran: {{ $tahunAjaran }}</p>
            <p>Dicetak pada: {{ now()->translatedFormat('l, d F Y H:i') }}</p>
        </div>

        <!-- Tabel Nilai per Kelas per Mapel -->
        <div class="section-title">Rekapitulasi Rata-rata Nilai per Kelas per Mata Pelajaran</div>
        <table>
            <thead>
                <tr>
                    <th class="text-left">Kelas</th>
                    <th>Tingkat</th>
                    <th class="text-left">Mata Pelajaran</th>
                    <th>Tugas</th>
                    <th>Kuis</th>
                    <th>UTS</th>
                    <th>UAS</th>
                    <th>Rata-rata</th>
                </tr>
            </thead>
            <tbody>
                @forelse($nilaiPerKelas as $n)
                <tr>
                    <td class="text-left highlight">{{ $n->nama_kelas }}</td>
                    <td>{{ $n->tingkat }}</td>
                    <td class="text-left">{{ $n->nama_mapel }}</td>
                    <td>{{ $n->avg_tugas }}</td>
                    <td>{{ $n->avg_kuis }}</td>
                    <td>{{ $n->avg_uts }}</td>
                    <td>{{ $n->avg_uas }}</td>
                    <td class="{{ $n->avg_total >= 75 ? 'good' : ($n->avg_total >= 60 ? 'warn' : 'bad') }}" style="font-weight: bold;">
                        {{ $n->avg_total }}
                    </td>
                </tr>
                @empty
                <tr><td colspan="8">Belum ada data nilai untuk filter ini.</td></tr>
                @endforelse
            </tbody>
        </table>

        <div class="footer">
            Dokumen ini digenerate otomatis oleh Sistem Monitoring EduConnect.
        </div>
    </div>
</body>
</html>
