<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Presensi Siswa</title>
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
            table thead th {
                background: #03045E !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .row-genap {
                background: #f0f4ff !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }

        /* ===== KOP ===== */
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

        /* ===== TABEL ===== */
        .row-ganjil { background: white; }
        .row-genap { background: #f0f4ff; }

        /* ===== BADGE STATUS ===== */
        .badge {
            padding: 2px 10px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 9pt;
            color: white;
            display: inline-block;
        }
        .badge-H { background: #10B981; }
        .badge-S { background: #F59E0B; }
        .badge-I { background: #3B82F6; }
        .badge-A { background: #EF4444; }
        .badge-D { background: #8B5CF6; }
    </style>
</head>
<body>

    {{-- TOMBOL AKSI --}}
    <div class="no-print" style="width:210mm; margin: 16px auto; display:flex; gap:10px;">
        <button onclick="window.print()"
            style="background:#03045E; color:white; padding:10px 24px; border:none; border-radius:8px; font-weight:700; cursor:pointer; font-size:13px;">
            🖨️ Cetak / Simpan PDF
        </button>
        <a href="{{ route('presensi.index') }}"
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
            <span>Laporan Presensi Siswa</span>
        </div>

        {{-- INFO SISWA --}}
        @php $first = $presensis->first(); @endphp

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
                <span style="font-weight:600; color:#03045E; width:110px; flex-shrink:0;">Nama Siswa</span>
                <span>: {{ $first->siswa->nama_lengkap ?? '-' }}</span>
            </div>
            <div style="display:flex; gap:6px; padding: 3px 0; border-bottom: 1px solid #e5e7eb;">
                <span style="font-weight:600; color:#03045E; width:110px; flex-shrink:0;">NISN</span>
                <span>: {{ $first->siswa_nisn ?? '-' }}</span>
            </div>
            <div style="display:flex; gap:6px; padding: 3px 0;">
                <span style="font-weight:600; color:#03045E; width:110px; flex-shrink:0;">Kelas</span>
                <span>: {{ $first->kelas->nama_kelas ?? '-' }}</span>
            </div>
            <div style="display:flex; gap:6px; padding: 3px 0;">
                <span style="font-weight:600; color:#03045E; width:110px; flex-shrink:0;">Tanggal Cetak</span>
                <span>: {{ \Carbon\Carbon::now()->translatedFormat('d F Y H:i') }}</span>
            </div>
        </div>

        {{-- TABEL LOG ABSENSI --}}
        <table style="
            width: 100%;
            border-collapse: collapse;
            font-size: 10pt;
            margin-bottom: 14px;
        ">
            <thead>
                <tr>
                    <th style="background:#03045E; color:white; padding:8px 10px; text-align:center; border:1px solid #03045E; width:4%;">No</th>
                    <th style="background:#03045E; color:white; padding:8px 10px; text-align:left; border:1px solid #03045E; width:18%;">Tanggal</th>
                    <th style="background:#03045E; color:white; padding:8px 10px; text-align:left; border:1px solid #03045E; width:25%;">Mata Pelajaran</th>
                    <th style="background:#03045E; color:white; padding:8px 10px; text-align:center; border:1px solid #03045E; width:12%;">Status</th>
                    <th style="background:#03045E; color:white; padding:8px 10px; text-align:left; border:1px solid #03045E;">Catatan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($presensis as $i => $p)
                <tr class="{{ $i % 2 == 0 ? 'row-ganjil' : 'row-genap' }}">
                    <td style="padding:7px 10px; border:1px solid #ccc; text-align:center;">{{ $i + 1 }}</td>
                    <td style="padding:7px 10px; border:1px solid #ccc;">{{ \Carbon\Carbon::parse($p->tanggal)->translatedFormat('d F Y') }}</td>
                    <td style="padding:7px 10px; border:1px solid #ccc; font-weight:600;">{{ $p->mapel->nama_mapel ?? '-' }}</td>
                    <td style="padding:7px 10px; border:1px solid #ccc; text-align:center;">
                        <span class="badge badge-{{ $p->status }}">
                            {{ $p->status == 'H' ? 'Hadir' : ($p->status == 'S' ? 'Sakit' : ($p->status == 'I' ? 'Izin' : ($p->status == 'A' ? 'Alpa' : 'Dispen'))) }}
                        </span>
                    </td>
                    <td style="padding:7px 10px; border:1px solid #ccc; font-style:italic; color:#555; font-size:9pt;">{{ $p->catatan ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="padding:30px; text-align:center; color:#999; font-style:italic;">
                        Belum ada data presensi yang tercatat.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{-- REKAP PER MAPEL --}}
        @php
            $rekapMapel = $presensis->groupBy(fn($p) => $p->mapel->nama_mapel ?? '-');
        @endphp

        @if($presensis->count() > 0)
        <div style="margin-bottom: 16px;">
            <div style="
                font-size: 10pt;
                font-weight: 700;
                color: #03045E;
                margin-bottom: 6px;
                font-family: Arial, sans-serif;
            ">Rekap Per Mata Pelajaran</div>

            <table style="width:100%; border-collapse:collapse; font-size:10pt;">
                <thead>
                    <tr>
                        <th style="background:#03045E; color:white; padding:7px 10px; text-align:left; border:1px solid #03045E; width:4%;">No</th>
                        <th style="background:#03045E; color:white; padding:7px 10px; text-align:left; border:1px solid #03045E;">Mata Pelajaran</th>
                        <th style="background:#03045E; color:white; padding:7px 10px; text-align:center; border:1px solid #03045E; width:12%;">Total</th>
                        <th style="background:#03045E; color:white; padding:7px 10px; text-align:center; border:1px solid #03045E; width:10%;">Hadir</th>
                        <th style="background:#03045E; color:white; padding:7px 10px; text-align:center; border:1px solid #03045E; width:10%;">Sakit</th>
                        <th style="background:#03045E; color:white; padding:7px 10px; text-align:center; border:1px solid #03045E; width:10%;">Izin</th>
                        <th style="background:#03045E; color:white; padding:7px 10px; text-align:center; border:1px solid #03045E; width:10%;">Alpa</th>
                        <th style="background:#03045E; color:white; padding:7px 10px; text-align:center; border:1px solid #03045E; width:10%;">Dispen</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rekapMapel as $namaMapel => $data)
                    @php $no = $loop->index; @endphp
                    <tr class="{{ $no % 2 == 0 ? 'row-ganjil' : 'row-genap' }}">
                        <td style="padding:7px 10px; border:1px solid #ccc; text-align:center;">{{ $loop->iteration }}</td>
                        <td style="padding:7px 10px; border:1px solid #ccc; font-weight:600;">{{ $namaMapel }}</td>
                        <td style="padding:7px 10px; border:1px solid #ccc; text-align:center; font-weight:700; color:#03045E;">{{ $data->count() }}</td>
                        <td style="padding:7px 10px; border:1px solid #ccc; text-align:center;">{{ $data->where('status','H')->count() }}</td>
                        <td style="padding:7px 10px; border:1px solid #ccc; text-align:center;">{{ $data->where('status','S')->count() }}</td>
                        <td style="padding:7px 10px; border:1px solid #ccc; text-align:center;">{{ $data->where('status','I')->count() }}</td>
                        <td style="padding:7px 10px; border:1px solid #ccc; text-align:center;">{{ $data->where('status','A')->count() }}</td>
                        <td style="padding:7px 10px; border:1px solid #ccc; text-align:center;">{{ $data->where('status','D')->count() }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        {{-- TTD --}}
        <div style="margin-top: 30px; font-size: 10pt;">
            
            {{-- Tempat & Tanggal --}}
            <div style="text-align:right; margin-bottom: 20px;">
                Mungkid, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 10px; text-align: center;">
                
                {{-- Orang Tua --}}
                <div>
                    <div>Orang Tua / Wali</div>
                    <div style="height: 55px;"></div>
                    <div style="border-top: 1px solid #000; width: 75%; margin: 0 auto; padding-top: 4px;">
                        <div style="font-weight:600;">( ........................ )</div>
                    </div>
                </div>

                {{-- Wali Kelas --}}
                <div>
                    <div>Wali Kelas</div>
                    <div style="height: 55px;"></div>
                    <div style="border-top: 1px solid #000; width: 75%; margin: 0 auto; padding-top: 4px;">
                        <div style="font-weight:600;">{{ $first->kelas->waliKelas->name ?? '( ........................ )' }}</div>
                    </div>
                </div>

                {{-- Kepala Sekolah --}}
                <div>
                    <div>Kepala Sekolah</div>
                    <div style="height: 55px;"></div>
                    <div style="border-top: 1px solid #000; width: 75%; margin: 0 auto; padding-top: 4px;">
                        <div style="font-weight:600;">( ........................ )</div>
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