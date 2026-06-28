<x-app-layout>
    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-4">
        <a href="{{ route('dashboard') }}" class="hover:text-[#03045E] font-medium flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Dashboard
        </a>
        <span>›</span>
        <span class="text-[#03045E] font-bold">Rekapitulasi Nilai</span>
    </div>

    <!-- Header -->
    <div class="mb-6 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
        <div>
            <h2 class="text-3xl font-black text-[#03045E] tracking-tight">Rekapitulasi Nilai Akademik</h2>
            <p class="text-gray-500 text-sm mt-1">Melihat daftar nilai akademik anak Anda secara lengkap.</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('nilai.cetak') }}" target="_blank" class="px-6 py-3 bg-emerald-500 text-white rounded-full font-bold shadow-lg hover:scale-105 transition-all flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                CETAK RAPOR
            </a>
        </div>
    </div>

    <form method="GET" action="{{ route('nilai.index') }}" class="flex gap-3 mb-6 flex-wrap">
        <div class="flex-1 relative min-w-[200px]">
            <input type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari nama anak atau mata pelajaran..."
                class="w-full rounded-full border-gray-200 pl-12 pr-4 py-3 focus:ring-[#03045E] focus:border-[#03045E] shadow-sm">
            <svg class="w-5 h-5 absolute left-4 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </div>

        {{-- Filter Tahun Ajaran --}}
        <select name="tahun_ajaran" class="rounded-full border-gray-200 px-6 py-3 focus:ring-[#03045E] focus:border-[#03045E] shadow-sm">
            <option value="">Semua Tahun Ajaran</option>
            @php
                $tahunSekarang = date('Y');
                $tahunMulai = $tahunSekarang - 5;
            @endphp
            @for($tahun = $tahunMulai; $tahun <= $tahunSekarang + 1; $tahun++)
                <option value="{{ $tahun }}/{{ $tahun+1 }}" {{ request('tahun_ajaran') == $tahun.'/'.($tahun+1) ? 'selected' : '' }}>
                    {{ $tahun }}/{{ $tahun+1 }}
                </option>
            @endfor
        </select>

        <button type="submit" class="px-6 py-3 bg-[#03045E] text-white rounded-full font-bold shadow-lg hover:scale-105 transition-all">
            Cari
        </button>

        @if(request()->filled('search') || request()->filled('tahun_ajaran'))
            <a href="{{ route('nilai.index') }}"
            class="px-6 py-3 bg-gray-200 text-gray-700 rounded-full font-bold shadow-sm hover:scale-105 transition-all">
                Reset
            </a>
        @endif
    </form>

    <div class="bg-white rounded-[2rem] shadow-sm overflow-hidden p-6">
        <div class="overflow-x-auto">
            <table class="w-full border-separate border-spacing-y-3 min-w-[800px]">
                <thead>
                    <tr class="text-white text-sm uppercase tracking-widest">
                        <th class="bg-[#03045E] p-4 rounded-l-full text-left pl-6">Siswa & Kelas</th>
                        <th class="bg-[#03045E] p-4 text-left">Mata Pelajaran & TA</th>
                        <th class="bg-[#03045E] p-4 text-center">Tugas</th>
                        <th class="bg-[#03045E] p-4 text-center">Kuis</th>
                        <th class="bg-[#03045E] p-4 text-center">UTS</th>
                        <th class="bg-[#03045E] p-4 rounded-r-full text-center">UAS</th>
                    </tr>
                </thead>
                <tbody class="text-[#03045E] font-medium">
                    @forelse($nilais as $n)
                    <tr class="bg-gray-50 hover:bg-white hover:shadow-md transition-all group">
                        <td class="p-4 rounded-l-2xl pl-6">
                            <div class="font-bold text-lg">{{ $n->siswa->nama_lengkap ?? '-' }}</div>
                            <div class="text-xs text-gray-500">Kelas: {{ $n->kelas->nama_kelas ?? '-' }} | NISN: {{ $n->siswa_nisn }}</div>
                        </td>
                        <td class="p-4">
                            <div class="font-bold">{{ $n->mapel->nama_mapel ?? '-' }}</div>
                            <div class="text-xs text-gray-500">{{ $n->semester }} - {{ $n->tahun_ajaran }}</div>
                        </td>
                        <td class="p-4 text-center">
                            <span class="px-3 py-1 rounded-full font-bold text-xs {{ $n->tugas < 75 ? 'bg-red-100 text-red-600' : 'bg-emerald-100 text-emerald-600' }}">{{ $n->tugas }}</span>
                        </td>
                        <td class="p-4 text-center">
                            <span class="px-3 py-1 rounded-full font-bold text-xs {{ $n->kuis < 75 ? 'bg-red-100 text-red-600' : 'bg-emerald-100 text-emerald-600' }}">{{ $n->kuis }}</span>
                        </td>
                        <td class="p-4 text-center">
                            <span class="px-3 py-1 rounded-full font-bold text-xs {{ $n->uts < 75 ? 'bg-red-100 text-red-600' : 'bg-emerald-100 text-emerald-600' }}">{{ $n->uts }}</span>
                        </td>
                        <td class="p-4 rounded-r-2xl text-center">
                            <span class="px-3 py-1 rounded-full font-bold text-xs {{ $n->uas < 75 ? 'bg-red-100 text-red-600' : 'bg-emerald-100 text-emerald-600' }}">{{ $n->uas }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                    <td colspan="6" class="p-8 text-center text-gray-400">
                        <div class="flex flex-col items-center gap-2">
                            <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                            <span class="font-bold text-gray-400">
                                Tidak ada data nilai ditemukan
                            </span>
                        </div>
                    </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-6">
            {{ $nilais->links() }}
        </div>
    </div>
</x-app-layout>
