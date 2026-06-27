<x-app-layout>
    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-4">
        <a href="{{ route('dashboard') }}" class="hover:text-[#03045E] font-medium flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Dashboard
        </a>
        <span>›</span>
        <a href="{{ route('presensi.index') }}" class="hover:text-[#03045E] font-medium">Manajemen Presensi</a>
        <span>›</span>
        <span class="text-[#03045E] font-bold">Detail Sesi</span>
    </div>

    <!-- Header -->
    <div class="mb-6">
        <h2 class="text-3xl font-black text-[#03045E] tracking-tight">Detail Presensi</h2>
        <div class="mt-4 flex flex-wrap gap-4 text-sm font-bold text-gray-700">
            <div class="flex items-center gap-2 bg-white px-4 py-2 rounded-full shadow-sm border border-gray-100">
                <svg class="w-5 h-5 text-[#03045E]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                {{ \Carbon\Carbon::parse($tanggal)->format('d F Y') }}
            </div>
            <div class="flex items-center gap-2 bg-white px-4 py-2 rounded-full shadow-sm border border-gray-100">
                <svg class="w-5 h-5 text-[#03045E]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                {{ $mapel->nama_mapel }}
            </div>
            <div class="flex items-center gap-2 bg-white px-4 py-2 rounded-full shadow-sm border border-gray-100">
                <svg class="w-5 h-5 text-[#03045E]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                Kelas {{ $kelas->nama_kelas }}
            </div>
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

    <div class="bg-white rounded-[2rem] shadow-sm overflow-hidden p-6">
        <div class="overflow-x-auto">
            <table class="w-full border-separate border-spacing-y-3">
                <thead>
                    <tr class="text-white text-sm uppercase tracking-widest">
                        <th class="bg-[#03045E] p-4 rounded-l-full text-left pl-6">Siswa</th>
                        <th class="bg-[#03045E] p-4 text-center">Status</th>
                        <th class="bg-[#03045E] p-4 text-center">Catatan</th>
                        <th class="bg-[#03045E] p-4 rounded-r-full text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-[#03045E] font-medium">
                    @forelse($presensis as $p)
                    <tr class="bg-gray-50 hover:bg-gray-100 transition-all">
                        <td class="p-4 rounded-l-2xl pl-6">
                            <div class="font-bold text-lg">{{ $p->siswa->nama_lengkap ?? '-' }}</div>
                            <div class="text-sm text-gray-500 font-normal">NISN: {{ $p->siswa_nisn }}</div>
                        </td>
                        <td class="p-4 text-center">
                            @if($p->status == 'H') <span class="bg-emerald-100 text-emerald-700 font-bold px-4 py-2 rounded-full text-xs uppercase">Hadir</span>
                            @elseif($p->status == 'S') <span class="bg-amber-100 text-amber-700 font-bold px-4 py-2 rounded-full text-xs uppercase">Sakit</span>
                            @elseif($p->status == 'I') <span class="bg-blue-100 text-blue-700 font-bold px-4 py-2 rounded-full text-xs uppercase">Izin</span>
                            @elseif($p->status == 'A') <span class="bg-red-100 text-red-700 font-bold px-4 py-2 rounded-full text-xs uppercase">Alpa</span>
                            @elseif($p->status == 'D') <span class="bg-purple-100 text-purple-700 font-bold px-4 py-2 rounded-full text-xs uppercase">Dispensasi</span>
                            @endif
                        </td>
                        <td class="p-4 text-center text-sm">
                            {{ $p->catatan ?? '-' }}
                        </td>
                        <td class="p-4 rounded-r-2xl text-center">
                            <div class="flex justify-center gap-2">
                                @if(auth()->user()->hasRole('guru-mapel') || auth()->user()->hasRole('wali-kelas'))
                                    <a href="{{ route('presensi.edit', $p->id) }}" class="p-2 bg-amber-100 text-amber-600 rounded-xl hover:bg-amber-500 hover:text-white transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                @endif
                                
                                @if(auth()->user()->hasRole('guru-mapel'))
                                    <form action="{{ route('presensi.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data presensi ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-2 bg-red-100 text-red-600 rounded-xl hover:bg-red-500 hover:text-white transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="p-8 text-center text-gray-400">
                            Data tidak ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
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
