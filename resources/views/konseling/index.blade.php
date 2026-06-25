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
            <h2 class="text-3xl font-black text-[#03045E] tracking-tight uppercase">Jadwal & Sesi Konseling</h2>
            <p class="text-gray-500">Kelola jadwal bimbingan dan konseling siswa.</p>
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
                    <td colspan="5" class="p-10 text-center text-gray-400 font-medium italic">Belum ada data konseling.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-app-layout>
