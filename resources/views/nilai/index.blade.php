<x-app-layout>
    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-4">
        <a href="{{ route('dashboard') }}" class="hover:text-[#03045E] font-medium flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Dashboard
        </a>
        <span>›</span>
        <span class="text-[#03045E] font-bold">Riwayat Nilai</span>
    </div>

    <!-- Header -->
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-black text-[#03045E] tracking-tight uppercase">Riwayat Nilai Akademik</h2>
            <p class="text-gray-500">Melihat daftar nilai akademik siswa secara lengkap.</p>
        </div>
        <div class="flex gap-3">
            @if(auth()->user()->hasRole('guru-mapel'))
                <a href="{{ route('nilai.create') }}" class="px-6 py-3 bg-[#03045E] text-white rounded-full font-bold shadow-lg hover:scale-105 transition-all flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    INPUT / EDIT MASSAL
                </a>
            @endif
            @if(auth()->user()->hasRole('orang-tua') || auth()->user()->hasRole('wali-kelas'))
                <a href="{{ route('nilai.cetak') }}" target="_blank" class="px-6 py-3 bg-emerald-500 text-white rounded-full font-bold shadow-lg hover:scale-105 transition-all flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    CETAK RAPOR
                </a>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-500 text-white rounded-2xl shadow-md font-bold flex items-center gap-2">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-[2rem] shadow-sm p-6 border border-gray-100 overflow-x-auto">
        <table class="w-full border-separate border-spacing-y-3 min-w-[800px]">
            <thead>
                <tr class="text-white text-xs uppercase tracking-[0.1em] font-black">
                    <th class="bg-[#03045E] p-4 rounded-l-full text-left pl-6">Siswa & Kelas</th>
                    <th class="bg-[#03045E] p-4 text-left">Mapel & TA</th>
                    <th class="bg-[#03045E] p-4 text-center">Tugas</th>
                    <th class="bg-[#03045E] p-4 text-center">Kuis</th>
                    <th class="bg-[#03045E] p-4 text-center">UTS</th>
                    <th class="bg-[#03045E] p-4 text-center">UAS</th>
                    <th class="bg-[#03045E] p-4 rounded-r-full text-center">Aksi</th>
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
                    <td class="p-4 text-center">
                        <span class="px-3 py-1 rounded-full font-bold text-xs {{ $n->uas < 75 ? 'bg-red-100 text-red-600' : 'bg-emerald-100 text-emerald-600' }}">{{ $n->uas }}</span>
                    </td>
                    <td class="p-4 rounded-r-2xl text-center">
                        <div class="flex justify-center gap-2">
                            @if(auth()->user()->hasRole('guru-mapel') || auth()->user()->hasRole('wali-kelas'))
                                <a href="{{ route('nilai.edit', $n->id) }}" class="px-3 py-2 bg-amber-100 text-amber-600 rounded-xl hover:bg-amber-500 hover:text-white transition-all font-bold text-xs">Edit</a>
                            @endif
                            @if(auth()->user()->hasRole('guru-mapel'))
                                <form action="{{ route('nilai.destroy', $n->id) }}" method="POST" onsubmit="return confirm('Hapus data nilai ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="px-3 py-2 bg-red-100 text-red-600 rounded-xl hover:bg-red-500 hover:text-white transition-all font-bold text-xs">Hapus</button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="p-10 text-center text-gray-400 font-medium italic">Belum ada data nilai tercatat.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-6">
            {{ $nilais->links() }}
        </div>
    </div>
</x-app-layout>
