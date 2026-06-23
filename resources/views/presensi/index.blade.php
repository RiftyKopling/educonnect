<x-app-layout>
    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-4">
        <a href="{{ route('dashboard') }}" class="hover:text-[#03045E] font-medium flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Dashboard
        </a>
        <span>›</span>
        <span class="text-[#03045E] font-bold">Riwayat Presensi</span>
    </div>

    <!-- Header -->
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-black text-[#03045E] tracking-tight uppercase">Riwayat Presensi</h2>
            <p class="text-gray-500">Melihat daftar riwayat presensi siswa per mata pelajaran.</p>
        </div>
        <div class="flex gap-3">
            @if(auth()->user()->hasRole('guru-mapel'))
                <a href="{{ route('presensi.create') }}" class="px-6 py-3 bg-[#03045E] text-white rounded-full font-bold shadow-lg hover:scale-105 transition-all flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    INPUT BARU
                </a>
            @endif
            @if(auth()->user()->hasRole('orang-tua') || auth()->user()->hasRole('wali-kelas'))
                <a href="{{ route('presensi.cetak') }}" target="_blank" class="px-6 py-3 bg-emerald-500 text-white rounded-full font-bold shadow-lg hover:scale-105 transition-all flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    CETAK LAPORAN
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

    <div class="bg-white rounded-[2rem] shadow-sm p-6 border border-gray-100">
        <table class="w-full border-separate border-spacing-y-3">
            <thead>
                <tr class="text-white text-xs uppercase tracking-[0.2em] font-black">
                    <th class="bg-[#03045E] p-5 rounded-l-full text-left pl-8">Tanggal</th>
                    <th class="bg-[#03045E] p-5 text-left">Siswa</th>
                    <th class="bg-[#03045E] p-5 text-left">Mapel & Kelas</th>
                    <th class="bg-[#03045E] p-5 text-center">Status</th>
                    <th class="bg-[#03045E] p-5 rounded-r-full text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-[#03045E] font-medium">
                @forelse($presensis as $p)
                <tr class="bg-gray-50 hover:bg-white hover:shadow-md transition-all group">
                    <td class="p-5 rounded-l-2xl pl-8 whitespace-nowrap">{{ \Carbon\Carbon::parse($p->tanggal)->format('d M Y') }}</td>
                    <td class="p-5">
                        <div class="font-bold text-lg">{{ $p->siswa->nama_lengkap ?? '-' }}</div>
                        <div class="text-sm text-gray-500 font-normal">NISN: {{ $p->siswa_nisn }}</div>
                    </td>
                    <td class="p-5">
                        <div class="font-bold">{{ $p->mapel->nama_mapel ?? '-' }}</div>
                        <div class="text-sm text-gray-500 font-normal">Kelas: {{ $p->kelas->nama_kelas ?? '-' }}</div>
                    </td>
                    <td class="p-5 text-center">
                        @if($p->status == 'H') <span class="bg-emerald-100 text-emerald-700 font-bold px-4 py-2 rounded-full text-xs">Hadir</span>
                        @elseif($p->status == 'S') <span class="bg-amber-100 text-amber-700 font-bold px-4 py-2 rounded-full text-xs">Sakit</span>
                        @elseif($p->status == 'I') <span class="bg-blue-100 text-blue-700 font-bold px-4 py-2 rounded-full text-xs">Izin</span>
                        @elseif($p->status == 'A') <span class="bg-red-100 text-red-700 font-bold px-4 py-2 rounded-full text-xs">Alpa</span>
                        @elseif($p->status == 'D') <span class="bg-purple-100 text-purple-700 font-bold px-4 py-2 rounded-full text-xs">Dispensasi</span>
                        @endif
                    </td>
                    <td class="p-5 rounded-r-2xl text-center">
                        <div class="flex justify-center gap-2">
                            @if(auth()->user()->hasRole('guru-mapel') || auth()->user()->hasRole('wali-kelas'))
                                <a href="{{ route('presensi.edit', $p->id) }}" class="px-4 py-2 bg-amber-100 text-amber-600 rounded-xl hover:bg-amber-500 hover:text-white transition-all font-bold text-xs">Edit</a>
                            @endif
                            
                            @if(auth()->user()->hasRole('guru-mapel'))
                                <form action="{{ route('presensi.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data presensi ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="px-4 py-2 bg-red-100 text-red-600 rounded-xl hover:bg-red-500 hover:text-white transition-all font-bold text-xs">Hapus</button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-10 text-center text-gray-400 font-medium italic">Belum ada data presensi yang sesuai.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-6">
            {{ $presensis->links() }}
        </div>
    </div>
</x-app-layout>
