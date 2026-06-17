<x-app-layout>
    <!-- Simplicity is the ultimate sophistication. - Leonardo da Vinci -->
    <div class="mb-8 flex flex-col md:flex-row justify-between items-center gap-4">
        <div>
            <h2 class="text-3xl font-black text-[#03045E] tracking-tight uppercase">Riwayat Presensi</h2>
            <p class="text-gray-500 font-medium">Laporan kehadiran siswa per mata pelajaran.</p>
        </div>
        
        <div class="flex gap-3">
            @if(in_array(auth()->user()->role?->slug, ['guru-mapel', 'wali-kelas']))
            <a href="{{ route('presensi.create') }}" class="px-8 py-3 bg-[#03045E] text-white rounded-full font-bold shadow-lg hover:scale-105 transition-all flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                TAMBAH PRESENSI KELAS
            </a>
            @endif

            @if(auth()->user()->role?->slug == 'orang-tua')
            <a href="{{ route('presensi.report') }}" target="_blank" class="px-8 py-3 bg-green-600 hover:bg-green-700 text-white rounded-full font-bold shadow-lg hover:scale-105 transition-all flex items-center gap-2 text-sm tracking-widest">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                CETAK LAPORAN
            </a>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-500 text-white rounded-2xl shadow-md flex items-center gap-3 font-bold italic">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-[2rem] shadow-sm p-6 border border-gray-100 overflow-x-auto">
        <table class="w-full border-separate border-spacing-y-3">
            <thead>
                <tr class="text-white text-[10px] uppercase tracking-[0.2em] font-black">
                    <th class="bg-[#03045E] p-4 rounded-l-full text-left pl-8">Siswa</th>
                    <th class="bg-[#03045E] p-4 text-left">Mapel / Kelas</th>
                    <th class="bg-[#03045E] p-4 text-center">Status</th>
                    <th class="bg-[#03045E] p-4 text-left">Guru & Keterangan</th>
                    @if(in_array(auth()->user()->role?->slug, ['guru-mapel', 'wali-kelas']))
                    <th class="bg-[#03045E] p-4 rounded-r-full text-center">Aksi</th>
                    @else
                    <th class="bg-[#03045E] p-4 rounded-r-full text-center"></th>
                    @endif
                </tr>
            </thead>
            <tbody class="text-[#03045E] font-bold">
                @forelse($data_presensi as $p)
                <tr class="bg-gray-50 hover:bg-white hover:shadow-md transition-all">
                    <td class="p-4 rounded-l-2xl pl-8">
                        <p class="text-sm">{{ $p->siswa?->nama_lengkap }}</p>
                        <p class="text-[10px] text-gray-400">NISN: {{ $p->siswa_nisn }}</p>
                    </td>
                    <td class="p-4">
                        <p class="text-sm">{{ $p->mapel?->nama_mapel }}</p>
                        <p class="text-[10px] text-blue-500">{{ $p->kelas?->nama_kelas }} | {{ \Carbon\Carbon::parse($p->tanggal)->format('d M Y') }}</p>
                    </td>
                    <td class="p-4 text-center">
                        @php
                            $colors = ['H' => 'bg-green-500', 'S' => 'bg-blue-500', 'I' => 'bg-yellow-500', 'A' => 'bg-red-500', 'D' => 'bg-purple-500'];
                        @endphp
                        <span class="{{ $colors[$p->status] ?? 'bg-gray-500' }} text-white px-3 py-1 rounded-full text-xs">
                            {{ $p->status }}
                        </span>
                    </td>
                    <td class="p-4">
                        <p class="text-xs text-gray-600">Oleh: {{ $p->guru?->name }}</p>
                        <p class="text-[10px] italic text-gray-400">{{ $p->catatan ?? '-' }}</p>
                    </td>
                    
                    @if(in_array(auth()->user()->role?->slug, ['guru-mapel', 'wali-kelas']))
                    <td class="p-4 rounded-r-2xl text-center">
                        <div class="flex justify-center gap-2">
                            @if(auth()->id() == $p->guru_id || auth()->user()->role?->slug == 'wali-kelas')
                            <a href="{{ route('presensi.edit', $p->id) }}" class="p-2 bg-amber-100 text-amber-600 rounded-xl hover:bg-amber-500 hover:text-white transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </a>
                            @endif

                            @if(auth()->id() == $p->guru_id)
                            <form action="{{ route('presensi.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Hapus presensi ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-2 bg-red-100 text-red-600 rounded-xl hover:bg-red-500 hover:text-white transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                    @else
                    <td class="p-4 rounded-r-2xl text-center"></td>
                    @endif
                </tr>
                @empty
                <tr><td colspan="5" class="p-10 text-center italic text-gray-400">Belum ada data presensi.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-6">{{ $data_presensi->links() }}</div>
    </div>
</x-app-layout>
