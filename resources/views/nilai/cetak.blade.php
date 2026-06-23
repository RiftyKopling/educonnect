<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transkrip Nilai Siswa</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #03045E; padding-bottom: 20px; }
        .title { font-size: 24px; font-weight: bold; color: #03045E; margin: 0; }
        .subtitle { font-size: 14px; color: #666; margin-top: 5px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; font-size: 14px; }
        th { background-color: #03045E; color: white; }
        .text-center { text-align: center; }
        @media print {
            .no-print { display: none; }
            body { margin: 0; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1 class="title">TRANSKRIP NILAI AKADEMIK</h1>
        <p class="subtitle">Sistem Informasi Akademik EduConnect</p>
        <p class="subtitle">Tanggal Cetak: {{ \Carbon\Carbon::now()->format('d M Y H:i') }}</p>
    </div>

    <div style="margin-bottom: 20px;" class="no-print">
        <button onclick="window.print()" style="background: #03045E; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-weight: bold;">🖨️ Cetak Dokumen</button>
        <a href="{{ route('nilai.index') }}" style="margin-left: 10px; color: #666; text-decoration: none;">Kembali</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>Siswa</th>
                <th>Mata Pelajaran</th>
                <th>Tahun & Semester</th>
                <th class="text-center">Tugas</th>
                <th class="text-center">Kuis</th>
                <th class="text-center">UTS</th>
                <th class="text-center">UAS</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($nilais as $n)
            <tr>
                <td>
                    <strong>{{ $n->siswa->nama_lengkap ?? '-' }}</strong><br>
                    <small>NISN: {{ $n->siswa_nisn }}</small>
                </td>
                <td>{{ $n->mapel->nama_mapel ?? '-' }}</td>
                <td>{{ $n->tahun_ajaran }} ({{ $n->semester }})</td>
                <td class="text-center">{{ $n->tugas }}</td>
                <td class="text-center">{{ $n->kuis }}</td>
                <td class="text-center">{{ $n->uts }}</td>
                <td class="text-center">{{ $n->uas }}</td>
                <td>{{ $n->catatan ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center">Belum ada data nilai yang tercatat.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 50px; text-align: right;">
        <p>Mengetahui,</p>
        <br><br><br>
        <p>____________________</p>
        <p><strong>Pihak Sekolah</strong></p>
    </div>
</body>
</html>
