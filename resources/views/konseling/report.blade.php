<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Jadwal & Hasil Konseling</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background: #f0f0f0;
            color: #000;
        }

        .page {
            width: 210mm;
            min-height: 297mm;
            margin: 20px auto;
            background: #fff;
            padding: 15mm 20mm;
            box-shadow: 0 4px 20px rgba(0, 0, 0, .1);
        }

        @page {
            size: A4;
            margin: 15mm 20mm;
        }

        @media print {
            body {
                background: #fff;
            }

            .page {
                margin: 0;
                padding: 0;
                width: 100%;
                box-shadow: none;
            }

            .no-print {
                display: none !important;
            }
        }

        .kop {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 16px;
            border-bottom: 3px double #03045E;
            padding-bottom: 10px;
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

        .kop-text {
            text-align: center;
        }

        .nama-sekolah {
            font-size: 16pt;
            font-weight: 700;
            color: #03045E;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .alamat {
            font-size: 9pt;
            color: #444;
            margin-top: 3px;
        }

        .judul {
            text-align: center;
            margin: 10px 0 16px;
        }

        .judul span {
            display: inline-block;
            border-top: 2px solid #03045E;
            border-bottom: 2px solid #03045E;
            padding: 4px 20px;
            color: #03045E;
            font-size: 13pt;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .row-ganjil {
            background: #fff;
        }

        .row-genap {
            background: #f0f4ff;
        }

        .badge-selesai {
            background: #d1fae5;
            color: #065f46;
            padding: 2px 8px;
            border-radius: 999px;
            font-size: 8pt;
            font-weight: 700;
        }

        .badge-terjadwal {
            background: #dbeafe;
            color: #1d4ed8;
            padding: 2px 8px;
            border-radius: 999px;
            font-size: 8pt;
            font-weight: 700;
        }

        .badge-batal {
            background: #fee2e2;
            color: #991b1b;
            padding: 2px 8px;
            border-radius: 999px;
            font-size: 8pt;
            font-weight: 700;
        }

        .confidential {
            color: #999;
            font-style: italic;
        }
    </style>
</head>

<body>

    <div class="no-print" style="width:210mm;margin:16px auto;display:flex;gap:10px;">
        <button onclick="window.print()"
            style="background:#03045E;color:#fff;padding:10px 24px;border:none;border-radius:8px;font-weight:700;cursor:pointer;font-size:13px;">
            🖨️ Cetak / Simpan PDF
        </button>

        <a href="{{ route('konseling.index') }}"
            style="padding:10px 20px;background:#e5e7eb;color:#333;border-radius:8px;text-decoration:none;font-weight:600;font-size:13px;">
            ← Kembali
        </a>
    </div>

    <div class="page">

        {{-- KOP SURAT --}}
        <div class="kop">
            <img src="{{ asset('images/fix_logo_educonnect.png') }}">

            <div class="kop-divider"></div>

            <div class="kop-text">
                <div class="nama-sekolah">
                    Sekolah Menengah Pertama Negeri 2 Mungkid
                </div>

                <div class="alamat">
                    Jl. Karanggawang, RT.03/RW.14, Geran, Rambeanak,
                    Kec. Mungkid, Kabupaten Magelang, Jawa Tengah 56512
                </div>

                <div class="alamat">
                    Telp. 0293-788264
                </div>
            </div>

            <div class="kop-divider"></div>

            <img src="{{ asset('images/fix_logo_smpn2mungkid.png') }}">
        </div>

        <div class="judul">
            <span>Laporan Jadwal & Hasil Konseling</span>
        </div>

        {{-- INFO --}}
        <div style="
        display:grid;
        grid-template-columns:1fr 1fr;
        gap:4px 30px;
        border:1px solid #03045E;
        border-radius:4px;
        padding:10px 16px;
        margin-bottom:16px;
        font-size:10pt;
    ">

            <div style="display:flex;gap:6px;padding:3px 0;border-bottom:1px solid #e5e7eb;">
                <span style="width:110px;font-weight:600;color:#03045E;">Dicetak Oleh</span>
                <span>: {{ auth()->user()->name }}</span>
            </div>

            <div style="display:flex;gap:6px;padding:3px 0;border-bottom:1px solid #e5e7eb;">
                <span style="width:110px;font-weight:600;color:#03045E;">Tanggal Cetak</span>
                <span>: {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</span>
            </div>

            <div style="display:flex;gap:6px;padding:3px 0;">
                <span style="width:110px;font-weight:600;color:#03045E;">Total Jadwal</span>
                <span>: {{ $data->count() }} sesi</span>
            </div>

            <div style="display:flex;gap:6px;padding:3px 0;">
                <span style="width:110px;font-weight:600;color:#03045E;">Unit</span>
                <span>: Bimbingan dan Konseling</span>
            </div>

        </div>

        {{-- TABEL --}}
        <table style="
        width:100%;
        border-collapse:collapse;
        font-size:10pt;
        margin-bottom:14px;
    ">

            <thead>

                <tr>

                    <th style="background:#03045E;color:white;padding:8px;border:1px solid #03045E;width:4%;">
                        No
                    </th>

                    <th style="background:#03045E;color:white;padding:8px;border:1px solid #03045E;width:12%;">
                        Tanggal
                    </th>

                    <th style="background:#03045E;color:white;padding:8px;border:1px solid #03045E;width:18%;">
                        Siswa
                    </th>

                    <th style="background:#03045E;color:white;padding:8px;border:1px solid #03045E;width:16%;">
                        Layanan
                    </th>

                    <th style="background:#03045E;color:white;padding:8px;border:1px solid #03045E;">
                        Topik
                    </th>

                    <th style="background:#03045E;color:white;padding:8px;border:1px solid #03045E;width:10%;">
                        Status
                    </th>

                    <th style="background:#03045E;color:white;padding:8px;border:1px solid #03045E;width:16%;">
                        Guru BK
                    </th>

                </tr>

            </thead>

            <tbody>
                @forelse($data as $i => $k)

                @php
                $statusClass = $k->status == 'Selesai'
                ? 'badge-selesai'
                : ($k->status == 'Terjadwal'
                ? 'badge-terjadwal'
                : 'badge-batal');
                @endphp

                <tr class="{{ $i % 2 == 0 ? 'row-ganjil' : 'row-genap' }}">

                    <td style="padding:7px 10px;border:1px solid #ccc;text-align:center;">
                        {{ $i+1 }}
                    </td>

                    <td style="padding:7px 10px;border:1px solid #ccc;text-align:center;">
                        {{ \Carbon\Carbon::parse($k->tanggal)->translatedFormat('d M Y') }}<br>
                        <span style="font-size:8.5pt;color:#666;">
                            {{ \Carbon\Carbon::parse($k->tanggal)->format('H:i') }} WIB
                        </span>
                    </td>

                    <td style="padding:7px 10px;border:1px solid #ccc;">
                        <strong>{{ $k->siswa->nama_lengkap ?? '-' }}</strong><br>
                        <span style="font-size:8.5pt;color:#555;">
                            NISN : {{ $k->siswa_nisn }} |
                            Kelas : {{ $k->siswa->kelas->nama_kelas ?? '-' }}
                        </span>
                    </td>

                    <td style="padding:7px 10px;border:1px solid #ccc;">
                        <strong>{{ $k->jenis_layanan }}</strong>
                    </td>

                    <td style="padding:7px 10px;border:1px solid #ccc;">

                        <div style="font-weight:600;margin-bottom:3px;">
                            {{ $k->topik }}
                        </div>

                        @if($k->deskripsi_kasus)

                        <div style="font-size:8.5pt;color:#666;line-height:1.45;">

                            @if($k->deskripsi_kasus == '*** Dirahasiakan demi privasi siswa ***')

                            <span class="confidential">
                                {{ $k->deskripsi_kasus }}
                            </span>

                            @else

                            {{ $k->deskripsi_kasus }}

                            @endif

                        </div>

                        @endif

                        @if($k->tindak_lanjut)

                        <div style="margin-top:5px;font-size:8.5pt;">

                            <strong>Hasil :</strong><br>

                            @if($k->tindak_lanjut == '*** Dirahasiakan demi privasi siswa ***')

                            <span class="confidential">
                                {{ $k->tindak_lanjut }}
                            </span>

                            @else

                            {{ $k->tindak_lanjut }}

                            @endif

                        </div>

                        @endif

                    </td>

                    <td style="padding:7px 10px;border:1px solid #ccc;text-align:center;">
                        <span class="{{ $statusClass }}">
                            {{ $k->status }}
                        </span>
                    </td>

                    <td style="padding:7px 10px;border:1px solid #ccc;">
                        {{ $k->guruBk->name ?? '-' }}
                    </td>

                </tr>

                @empty

                <tr>

                    <td colspan="7"
                        style="padding:30px;text-align:center;color:#999;font-style:italic;">

                        Belum ada data jadwal konseling.

                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

        @php
        $totalTerjadwal = $data->where('status','Terjadwal')->count();
        $totalSelesai = $data->where('status','Selesai')->count();
        $totalBatal = $data->where('status','Batal')->count();
        @endphp

        @if($data->count())

        <div style="
                        font-size:10pt;
                        padding:8px 14px;
                        background:#eef1fb;
                        border-left:4px solid #03045E;
                        border-radius:3px;
                        margin-bottom:16px;
                        display:flex;
                        gap:20px;
                    ">

            <span>
                <strong style="color:#03045E;">
                    Total :
                </strong>
                {{ $data->count() }} sesi
            </span>

            <span>
                •
                <strong style="color:#1d4ed8;">
                    Terjadwal :
                </strong>
                {{ $totalTerjadwal }}
            </span>

            <span>
                •
                <strong style="color:#065f46;">
                    Selesai :
                </strong>
                {{ $totalSelesai }}
            </span>

            <span>
                •
                <strong style="color:#991b1b;">
                    Batal :
                </strong>
                {{ $totalBatal }}
            </span>

        </div>

        @endif

        <div style="margin-top:45px;display:flex;justify-content:flex-end;">

            <div style="width:270px;text-align:center;font-size:10pt;">

                <div>
                    Magelang,
                    {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
                </div>

                <div style="margin-top:4px;">
                    Guru Bimbingan dan Konseling
                </div>

                <div style="height:75px;"></div>

                <div style="
            border-top:1px solid #000;
            display:inline-block;
            padding-top:4px;
            min-width:180px;
            font-weight:bold;
        ">
                    {{ auth()->user()->name }}
                </div>

                <div style="margin-top:4px;font-size:9pt;color:#555;">
                    NIP : ______________________
                </div>

            </div>

        </div>

        <div style="
            margin-top:30px;
            border-top:1px solid #999;
            padding-top:8px;
            text-align:center;
            font-size:8.5pt;
            color:#666;
        ">

            Dokumen ini dibuat secara otomatis oleh
            <strong>EduConnect</strong>
            <br>

            Laporan Jadwal & Hasil Konseling —
            Dicetak pada
            {{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i') }}
            WIB

        </div>

    </div>

</body>

</html>