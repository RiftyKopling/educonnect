<x-app-layout>
    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-4">
        <a href="{{ route('dashboard') }}" class="hover:text-[#03045E] font-medium flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Dashboard
        </a>
        <span>›</span>
        <span class="text-[#03045E] font-bold">Catatan Pelanggaran</span>
    </div>

    <!-- Header -->
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-black text-[#03045E] tracking-tight">Catatan Pelanggaran Siswa</h2>
            <p class="text-gray-500 text-sm mt-1">Melihat rekam jejak perilaku siswa.</p>
        </div>
        <div class="flex gap-3">
            @if(auth()->user()->hasRole('guru-bk') || auth()->user()->hasRole('admin-sekolah'))
                <a href="{{ route('catatan-pelanggaran.create') }}" class="px-6 py-3 bg-[#03045E] text-white rounded-full font-bold shadow-lg hover:scale-105 transition-all flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    CATAT PELANGGARAN
                </a>
            @endif
            <a href="{{ route('catatan-pelanggaran.cetak') }}" target="_blank" class="px-6 py-3 bg-emerald-500 text-white rounded-full font-bold shadow-lg hover:scale-105 transition-all flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                CETAK LAPORAN
            </a>
        </div>
    </div>

    @if(session('success'))
        <div id="notif-sukses" class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-2xl flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
            <button onclick="tutupNotif()" class="text-green-700 hover:text-green-900 ml-4">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    @endif

    <form method="GET" action="{{ route('catatan-pelanggaran.index') }}" class="flex flex-wrap gap-3 mb-6">
        <select name="bulan" class="rounded-full border-gray-200 pl-6 pr-10 py-3 focus:ring-[#03045E] focus:border-[#03045E] shadow-sm">
            <option value="">Semua Bulan</option>
            @foreach(['1'=>'Januari','2'=>'Februari','3'=>'Maret','4'=>'April','5'=>'Mei','6'=>'Juni','7'=>'Juli','8'=>'Agustus','9'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'] as $key => $name)
                <option value="{{ $key }}" {{ request('bulan') == $key ? 'selected' : '' }}>{{ $name }}</option>
            @endforeach
        </select>

        <select name="tahun" class="rounded-full border-gray-200 pl-6 pr-10 py-3 focus:ring-[#03045E] focus:border-[#03045E] shadow-sm">
            <option value="">Semua Tahun</option>
            @for($i = date('Y') - 2; $i <= date('Y') + 1; $i++)
                <option value="{{ $i }}" {{ request('tahun') == $i ? 'selected' : '' }}>{{ $i }}</option>
            @endfor
        </select>

        <select name="pelanggaran_id" class="rounded-full border-gray-200 pl-6 pr-10 py-3 focus:ring-[#03045E] focus:border-[#03045E] shadow-sm">
            <option value="">Semua Pelanggaran</option>
            @foreach($pelanggaranList as $p)
                <option value="{{ $p->id }}" {{ request('pelanggaran_id') == $p->id ? 'selected' : '' }}>{{ $p->nama_pelanggaran }}</option>
            @endforeach
        </select>

        <select name="kelas_id" class="rounded-full border-gray-200 pl-6 pr-10 py-3 focus:ring-[#03045E] focus:border-[#03045E] shadow-sm">
            <option value="">Semua Kelas</option>
            @foreach($kelasList as $k)
                <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
            @endforeach
        </select>

        <button type="submit" class="px-6 py-3 bg-[#03045E] text-white rounded-full font-bold shadow-lg hover:scale-105 transition-all">Cari</button>

        @if(request()->hasAny(['bulan', 'tahun', 'pelanggaran_id', 'kelas_id']))
            <a href="{{ route('catatan-pelanggaran.index') }}" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-full font-bold shadow-sm hover:scale-105 transition-all">Reset</a>
        @endif
    </form>

    <div class="bg-white rounded-[2rem] shadow-sm p-6 border border-gray-100 overflow-x-auto">
        <table class="w-full border-separate border-spacing-y-3 min-w-[800px]">
            <thead>
                <tr class="text-white text-sm uppercase tracking-widest">
                    <th class="bg-[#03045E] p-4 rounded-l-full text-left pl-6">Tanggal</th>
                    <th class="bg-[#03045E] p-4 text-left">Siswa & Kelas</th>
                    <th class="bg-[#03045E] p-4 text-left">Pelanggaran</th>
                    <th class="bg-[#03045E] p-4 text-left">Guru Pencatat</th>
                    <th class="bg-[#03045E] p-4 rounded-r-full text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-[#03045E] font-medium">
                @forelse($catatanPelanggarans as $catatan)
                <tr class="bg-gray-50 hover:bg-white hover:shadow-md transition-all group">
                    <td class="p-4 rounded-l-2xl pl-6">
                        <div class="font-bold">{{ \Carbon\Carbon::parse($catatan->tanggal)->format('d M Y') }}</div>
                    </td>
                    <td class="p-4">
                        <div class="font-bold text-lg">{{ $catatan->siswa->nama_lengkap ?? '-' }}</div>
                        <div class="text-xs text-gray-500">Kelas: {{ $catatan->siswa->kelas->nama_kelas ?? '-' }}</div>
                    </td>
                    <td class="p-4">
                        <div class="font-bold">{{ $catatan->pelanggaran->nama_pelanggaran ?? '-' }}</div>
                        <span class="px-2 py-1 mt-1 inline-block rounded-full font-bold text-[10px] uppercase
                            {{ ($catatan->pelanggaran->kategori ?? '') == 'Ringan' ? 'bg-emerald-100 text-emerald-600' : 
                              (($catatan->pelanggaran->kategori ?? '') == 'Sedang' ? 'bg-amber-100 text-amber-600' : 'bg-red-100 text-red-600') }}">
                            {{ $catatan->pelanggaran->kategori ?? '-' }}
                        </span>
                    </td>
                    <td class="p-4">
                        <div class="font-bold">{{ $catatan->guruBk->name ?? '-' }}</div>
                    </td>
                    <td class="p-4 rounded-r-2xl text-center">
                        <a href="{{ route('catatan-pelanggaran.show', $catatan->id) }}" class="px-3 py-2 bg-blue-100 text-blue-600 rounded-xl hover:bg-blue-500 hover:text-white transition-all font-bold text-xs inline-block">Detail</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-10 text-center text-gray-400 font-medium italic">
                        <div class="flex flex-col items-center gap-2">
                            <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            <span class="font-bold text-gray-400">Tidak ada riwayat presensi ditemukan</span>
                            @if(request()->hasAny(['bulan', 'tahun', 'pelanggaran_id', 'kelas_id']))
                                <span class="text-sm text-gray-400">Coba ubah filter pencarian</span>
                                <a href="{{ route('catatan-pelanggaran.index') }}" class="mt-2 px-4 py-2 bg-gray-100 text-gray-600 rounded-full text-sm font-bold hover:bg-gray-200 transition-all">Reset Pencarian</a>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <script>
        function tutupNotif() {
            const notif = document.getElementById('notif-sukses');
            if (notif) {
                notif.style.transition = 'opacity 0.5s';
                notif.style.opacity = '0';
                setTimeout(() => notif.remove(), 500);
            }
        }
        setTimeout(tutupNotif, 5000);
    </script>

</x-app-layout>
