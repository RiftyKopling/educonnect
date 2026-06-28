<x-app-layout>
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-4">
        <a href="{{ route('dashboard') }}" class="hover:text-[#03045E] font-medium flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Dashboard
        </a>
        <span>›</span>
        <span class="text-[#03045E] font-bold">Jadwal Konseling</span>
    </div>

    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-black text-[#03045E] tracking-tight">Jadwal & Sesi Konseling</h2>
            <p class="text-gray-500 text-sm mt-1">Kelola jadwal bimbingan dan konseling siswa.</p>
        </div>
        <div class="flex gap-3">
            @if(auth()->user()->hasRole('guru-bk') || auth()->user()->hasRole('admin-sekolah'))
                <a href="{{ route('konseling.create') }}" class="px-6 py-3 bg-[#03045E] text-white rounded-full font-bold shadow-lg hover:scale-105 transition-all flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    BUAT JADWAL
                </a>
            @endif
            <a href="{{ route('konseling.cetak') }}" target="_blank" class="px-6 py-3 bg-emerald-500 text-white rounded-full font-bold shadow-lg hover:scale-105 transition-all flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                CETAK LAPORAN
            </a>
        </div>
    </div>

    <form method="GET" action="{{ route('konseling.index') }}" class="flex flex-wrap gap-3 mb-6">
        <div class="relative">
            <input type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari nama siswa..."
                class="rounded-full border-gray-200 pl-12 pr-4 py-3 focus:ring-[#03045E] focus:border-[#03045E] shadow-sm">
            <svg class="w-5 h-5 absolute left-4 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        </div>

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

        <select name="kelas_id" class="rounded-full border-gray-200 pl-6 pr-10 py-3 focus:ring-[#03045E] focus:border-[#03045E] shadow-sm">
            <option value="">Semua Kelas</option>
            @foreach($kelasList as $k)
                <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
            @endforeach
        </select>

        <select name="status" class="rounded-full border-gray-200 pl-6 pr-10 py-3 focus:ring-[#03045E] focus:border-[#03045E] shadow-sm">
            <option value="">Semua Status</option>
            <option value="Terjadwal" {{ request('status') == 'Terjadwal' ? 'selected' : '' }}>Terjadwal</option>
            <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
            <option value="Dibatalkan" {{ request('status') == 'Dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
        </select>

        <button type="submit" class="px-6 py-3 bg-[#03045E] text-white rounded-full font-bold shadow-lg hover:scale-105 transition-all">Cari</button>

        @if(request()->hasAny(['search', 'bulan', 'tahun', 'kelas_id', 'status']))
            <a href="{{ route('konseling.index') }}" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-full font-bold shadow-sm hover:scale-105 transition-all">Reset</a>
        @endif
    </form>

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

    <div class="bg-white rounded-[2rem] shadow-sm p-6 border border-gray-100 overflow-x-auto">
        <table class="w-full border-separate border-spacing-y-3 min-w-[800px]">
            <thead>
                <tr class="text-white text-sm uppercase tracking-widest">
                    <th class="bg-[#03045E] p-4 rounded-l-full text-left pl-6">Tanggal & Waktu</th>
                    <th class="bg-[#03045E] p-4 text-left">Siswa & Kelas</th>
                    <th class="bg-[#03045E] p-4 text-left">Layanan & Topik</th>
                    <th class="bg-[#03045E] p-4 text-center">Status</th>
                    <th class="bg-[#03045E] p-4 rounded-r-full text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-[#03045E] font-medium">
                @forelse($konselings as $k)
                <tr class="bg-gray-50 hover:bg-white hover:shadow-md transition-all group">
                    <td class="p-4 rounded-l-2xl pl-6">
                        <div class="font-bold">{{ \Carbon\Carbon::parse($k->tanggal)->format('d M Y') }}</div>
                        <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($k->tanggal)->format('H:i') }} WIB</div>
                    </td>
                    <td class="p-4">
                        <div class="font-bold text-lg">{{ $k->siswa->nama_lengkap ?? '-' }}</div>
                        <div class="text-xs text-gray-500">Kelas: {{ $k->siswa->kelas->nama_kelas ?? '-' }}</div>
                    </td>
                    <td class="p-4">
                        <div class="font-bold">{{ $k->jenis_layanan }}</div>
                        <div class="text-xs text-gray-500">{{ Str::limit($k->topik, 40) }}</div>
                    </td>
                    <td class="p-4 text-center">
                        <span class="px-3 py-1 rounded-full font-bold text-xs 
                            {{ $k->status == 'Selesai' ? 'bg-emerald-100 text-emerald-600' : 
                              ($k->status == 'Terjadwal' ? 'bg-blue-100 text-blue-600' : 'bg-red-100 text-red-600') }}">
                            {{ $k->status }}
                        </span>
                    </td>
                    <td class="p-4 rounded-r-2xl text-center">
                        <div class="flex justify-center gap-2">
                            <a href="{{ route('konseling.show', $k->id) }}" class="px-3 py-2 bg-blue-100 text-blue-600 rounded-xl hover:bg-blue-500 hover:text-white transition-all font-bold text-xs inline-block">Detail</a>
                            @if(auth()->user()->hasRole('guru-bk') || auth()->user()->hasRole('admin-sekolah'))
                                <a href="{{ route('konseling.edit', $k->id) }}" class="px-3 py-2 bg-amber-100 text-amber-600 rounded-xl hover:bg-amber-500 hover:text-white transition-all font-bold text-xs inline-block">Edit</a>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-10 text-center text-gray-400 font-medium italic">
                        <div class="flex flex-col items-center gap-2">
                            Belum ada data konseling.
                            @if(request()->hasAny(['search', 'bulan', 'tahun', 'kelas_id', 'status']))
                                <span class="text-sm text-gray-400">Coba ubah kata kunci pencarian atau filter</span>
                                <a href="{{ route('konseling.index') }}" class="mt-2 px-4 py-2 bg-gray-100 text-gray-600 rounded-full text-sm font-bold hover:bg-gray-200 transition-all">Reset Pencarian</a>
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
