<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Catatan Pelanggaran Siswa</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background: #f0f0f0;
            color: #000;
        }

        .page {
            width: 210mm;
            min-height: 297mm;
            margin: 20px auto;
            background: white;
            padding: 15mm 20mm;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }

        @page {
            size: A4;
            margin: 15mm 20mm;
        }

        @media print {
            body { background: white; }
            .page {
                margin: 0;
                padding: 0;
                box-shadow: none;
                width: 100%;
            }
            .no-print { display: none !important; }
        }

        .kop {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 16px;
            padding-bottom: 10px;
            border-bottom: 3px double #03045E;
            margin-bottom: 12px;
        }

        .kop img {
            width: 70px;
            height: 70px;
            object-fit: contain;
            flex-shrink: 0;
        }

        .kop-divider {
            width: 1px;
            height: 65px;
            background: #aaa;
            flex-shrink: 0;
        }

        .kop-text { text-align: center; }

        .kop-text .nama-sekolah {
            font-size: 16pt;
            font-weight: 700;
            color: #03045E;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-family: 'Arial', sans-serif;
        }

        .kop-text .alamat {
            font-size: 9pt;
            color: #444;
            margin-top: 3px;
        }

        .judul-dokumen {
            text-align: center;
            margin: 10px 0;
        }

        .judul-dokumen span {
            font-size: 13pt;
            font-weight: 700;
            color: #03045E;
            text-transform: uppercase;
            letter-spacing: 2px;
            border-top: 2px solid #03045E;
            border-bottom: 2px solid #03045E;
            padding: 4px 20px;
            display: inline-block;
            font-family: 'Arial', sans-serif;
        }

        .row-ganjil { background: white; }
        .row-genap { background: #f0f4ff; }
        .kat-ringan { padding:2px 8px; border-radius:999px; font-size:8pt; font-weight:700; background:#d1fae5; color:#065f46; }
        .kat-sedang { padding:2px 8px; border-radius:999px; font-size:8pt; font-weight:700; background:#fef3c7; color:#92400e; }
        .kat-berat  { padding:2px 8px; border-radius:999px; font-size:8pt; font-weight:700; background:#fee2e2; color:#991b1b; }
</style>
    </style>
</head>
<body>

    {{-- TOMBOL AKSI --}}
    <div class="no-print" style="width:210mm; margin: 16px auto; display:flex; gap:10px;">
        <button onclick="window.print()"
            style="background:#03045E; color:white; padding:10px 24px; border:none; border-radius:8px; font-weight:700; cursor:pointer; font-size:13px;">
            🖨️ Cetak / Simpan PDF
        </button>
        <a href="{{ route('catatan-pelanggaran.index') }}"
            style="padding:10px 20px; background:#e5e7eb; color:#333; border-radius:8px; text-decoration:none; font-weight:600; font-size:13px;">
            ← Kembali
        </a>
    </div>

    <div class="page">

        {{-- KOP SURAT --}}
        <div class="kop">
            <img src="{{ asset('images/fix_logo_educonnect.png') }}" alt="Logo EduConnect">
            <div class="kop-divider"></div>
            <div class="kop-text">
                <div class="nama-sekolah">Sekolah Menengah Pertama Negeri 2 Mungkid</div>
                <div class="alamat">Jl. Karanggawang, RT.03/RW.14, Geran, Rambeanak, Kec. Mungkid, Kabupaten Magelang, Jawa Tengah 56512</div>
                <div class="alamat">Telp. 0293-788264</div>
            </div>
            <div class="kop-divider"></div>
            <img src="{{ asset('images/fix_logo_smpn2mungkid.png') }}" alt="Logo Sekolah">
        </div>

        <div class="judul-dokumen">
            <span>Laporan Catatan Pelanggaran Siswa</span>
        </div>

        {{-- INFO LAPORAN --}}
        <div style="
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4px 30px;
            border: 1px solid #03045E;
            border-radius: 4px;
            padding: 10px 16px;
            margin-bottom: 16px;
            font-size: 10pt;
        ">
            <div style="display:flex; gap:6px; padding: 3px 0; border-bottom: 1px solid #e5e7eb;">
                <span style="font-weight:600; color:#03045E; width:110px; flex-shrink:0;">Dicetak Oleh</span>
                <span>: {{ auth()->user()->name }}</span>
            </div>
            <div style="display:flex; gap:6px; padding: 3px 0; border-bottom: 1px solid #e5e7eb;">
                <span style="font-weight:600; color:#03045E; width:110px; flex-shrink:0;">Tanggal Cetak</span>
                <span>: {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</span>
            </div>
            <div style="display:flex; gap:6px; padding: 3px 0;">
                <span style="font-weight:600; color:#03045E; width:110px; flex-shrink:0;">Total Catatan</span>
                <span>: {{ $data->count() }} catatan</span>
            </div>
            <div style="display:flex; gap:6px; padding: 3px 0;">
                <span style="font-weight:600; color:#03045E; width:110px; flex-shrink:0;">Unit</span>
                <span>: Bimbingan dan Konseling</span>
            </div>
        </div>

        {{-- TABEL --}}
        <table style="
            width: 100%;
            border-collapse: collapse;
            font-size: 10pt;
            margin-bottom: 14px;
        ">
            <thead>
                <tr>
                    <th style="background:#03045E; color:white; padding:8px 10px; text-align:center; border:1px solid #03045E; width:4%;">No</th>
                    <th style="background:#03045E; color:white; padding:8px 10px; text-align:center; border:1px solid #03045E; width:12%;">Tanggal</th>
                    <th style="background:#03045E; color:white; padding:8px 10px; text-align:left; border:1px solid #03045E; width:20%;">Siswa & Kelas</th>
                    <th style="background:#03045E; color:white; padding:8px 10px; text-align:left; border:1px solid #03045E; width:20%;">Pelanggaran</th>
                    <th style="background:#03045E; color:white; padding:8px 10px; text-align:center; border:1px solid #03045E; width:10%;">Kategori</th>
                    <th style="background:#03045E; color:white; padding:8px 10px; text-align:left; border:1px solid #03045E;">Keterangan</th>
                    <th style="background:#03045E; color:white; padding:8px 10px; text-align:left; border:1px solid #03045E; width:15%;">Pencatat</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $i => $catatan)
                <tr class="{{ $i % 2 == 0 ? 'row-ganjil' : 'row-genap' }}">
                    <td style="padding:7px 10px; border:1px solid #ccc; text-align:center;">{{ $i + 1 }}</td>
                    <td style="padding:7px 10px; border:1px solid #ccc; text-align:center;">
                        {{ \Carbon\Carbon::parse($catatan->tanggal)->translatedFormat('d M Y') }}
                    </td>
                    <td style="padding:7px 10px; border:1px solid #ccc;">
                        <strong>{{ $catatan->siswa->nama_lengkap ?? '-' }}</strong><br>
                        <span style="font-size:8.5pt; color:#555;">NISN: {{ $catatan->siswa_nisn }} | Kelas: {{ $catatan->siswa->kelas->nama_kelas ?? '-' }}</span>
                    </td>
                    <td style="padding:7px 10px; border:1px solid #ccc; font-weight:600;">
                        {{ $catatan->pelanggaran->nama_pelanggaran ?? '-' }}
                    </td>
                    <td style="padding:7px 10px; border:1px solid #ccc; text-align:center;">
                        @php
                            $kat = $catatan->pelanggaran->kategori ?? '-';
                            $katClass = $kat == 'Ringan' ? 'kat-ringan' : ($kat == 'Sedang' ? 'kat-sedang' : 'kat-berat');
                        @endphp
                        <span class="{{ $katClass }}">{{ $kat }}</span>
                    </td>
                    <td style="padding:7px 10px; border:1px solid #ccc; font-style:italic; color:#555; font-size:9pt;">
                        {{ $catatan->keterangan ?? '-' }}
                    </td>
                    <td style="padding:7px 10px; border:1px solid #ccc;">
                        {{ $catatan->guruBk->name ?? '-' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="padding:30px; text-align:center; color:#999; font-style:italic;">
                        Belum ada data catatan pelanggaran yang tercatat.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{-- REKAP --}}
        @php
            $totalRingan = $data->filter(fn($c) => ($c->pelanggaran->kategori ?? '') == 'Ringan')->count();
            $totalSedang = $data->filter(fn($c) => ($c->pelanggaran->kategori ?? '') == 'Sedang')->count();
            $totalBerat  = $data->filter(fn($c) => !in_array($c->pelanggaran->kategori ?? '', ['Ringan', 'Sedang']))->count();
        @endphp

        @if($data->count() > 0)
        <div style="
            font-size: 10pt;
            padding: 8px 14px;
            background: #eef1fb;
            border-left: 4px solid #03045E;
            border-radius: 3px;
            margin-bottom: 16px;
            display: flex;
            gap: 20px;
        ">
            <span><strong style="color:#03045E;">Total:</strong> {{ $data->count() }} catatan</span>
            <span>&bull; <strong style="color:#065f46;">Ringan:</strong> {{ $totalRingan }}</span>
            <span>&bull; <strong style="color:#92400e;">Sedang:</strong> {{ $totalSedang }}</span>
            <span>&bull; <strong style="color:#991b1b;">Berat:</strong> {{ $totalBerat }}</span>
        </div>
        @endif

        {{-- TTD --}}
        <div style="margin-top: 30px; font-size: 10pt;">
            <div style="text-align:right; margin-bottom: 20px;">
                Mungkid, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; text-align: center;">
                <div>
                    <div>Kepala Sekolah</div>
                    <div style="height: 55px;"></div>
                    <div style="border-top: 1px solid #000; width: 75%; margin: 0 auto; padding-top: 4px;">
                        <div style="font-weight:600;">( ........................ )</div>
                    </div>
                </div>
                <div>
                    <div style="display: none;">Mengetahui, </div>
                    <div>Guru Bimbingan Konseling</div>
                    <div style="height: 55px;"></div>
                    <div style="border-top: 1px solid #000; width: 75%; margin: 0 auto; padding-top: 4px;">
                        <div style="font-weight:600;">{{ auth()->user()->name }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- FOOTER --}}
        <div style="
            margin-top: 24px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 8pt;
            color: #aaa;
        ">
            Dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y H:i') }}
            &bull; EduConnect Academic System &bull; SMPN 2 Mungkid
        </div>
    </div>
</body>
</html>