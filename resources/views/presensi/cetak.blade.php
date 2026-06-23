<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Presensi Siswa</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #03045E; padding-bottom: 20px; }
        .title { font-size: 24px; font-weight: bold; color: #03045E; margin: 0; }
        .subtitle { font-size: 14px; color: #666; margin-top: 5px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; font-size: 14px; }
        th { background-color: #03045E; color: white; }
        .text-center { text-align: center; }
        .badge-H { background-color: #10B981; color: white; padding: 3px 10px; border-radius: 12px; font-weight: bold; font-size: 12px; }
        .badge-S { background-color: #F59E0B; color: white; padding: 3px 10px; border-radius: 12px; font-weight: bold; font-size: 12px; }
        .badge-I { background-color: #3B82F6; color: white; padding: 3px 10px; border-radius: 12px; font-weight: bold; font-size: 12px; }
        .badge-A { background-color: #EF4444; color: white; padding: 3px 10px; border-radius: 12px; font-weight: bold; font-size: 12px; }
        .badge-D { background-color: #8B5CF6; color: white; padding: 3px 10px; border-radius: 12px; font-weight: bold; font-size: 12px; }
        @media print {
            .no-print { display: none; }
            body { margin: 0; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1 class="title">LAPORAN PRESENSI SISWA</h1>
        <p class="subtitle">Sistem Informasi Akademik EduConnect</p>
        <p class="subtitle">Tanggal Cetak: {{ \Carbon\Carbon::now()->format('d M Y H:i') }}</p>
    </div>

    <div style="margin-bottom: 20px;" class="no-print">
        <button onclick="window.print()" style="background: #03045E; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-weight: bold;">🖨️ Cetak Dokumen</button>
        <a href="{{ route('presensi.index') }}" style="margin-left: 10px; color: #666; text-decoration: none;">Kembali</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Siswa</th>
                <th>Kelas</th>
                <th>Mata Pelajaran</th>
                <th class="text-center">Status</th>
                <th>Catatan Tambahan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($presensis as $p)
            <tr>
                <td>{{ \Carbon\Carbon::parse($p->tanggal)->format('d M Y') }}</td>
                <td>
                    <strong>{{ $p->siswa->nama_lengkap ?? '-' }}</strong><br>
                    <small>NISN: {{ $p->siswa_nisn }}</small>
                </td>
                <td>{{ $p->kelas->nama_kelas ?? '-' }}</td>
                <td>{{ $p->mapel->nama_mapel ?? '-' }}</td>
                <td class="text-center">
                    <span class="badge-{{ $p->status }}">
                        {{ $p->status == 'H' ? 'Hadir' : ($p->status == 'S' ? 'Sakit' : ($p->status == 'I' ? 'Izin' : ($p->status == 'A' ? 'Alpa' : 'Dispen'))) }}
                    </span>
                </td>
                <td>{{ $p->catatan ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">Belum ada data presensi yang tercatat.</td>
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
