<x-app-layout>
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-4">
        <a href="{{ route('dashboard') }}" class="hover:text-[#03045E] font-medium flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Dashboard
        </a>
        <span>›</span>
        <span class="text-[#03045E] font-bold">Materi Ajar</span>
    </div>

    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-black text-[#03045E] tracking-tight uppercase">Bank Materi Pribadi</h2>
            <p class="text-gray-500">Kelola dokumen materi dan tautan pembelajaran Anda.</p>
        </div>
        @if(auth()->user()->hasRole('guru-mapel'))
            <a href="{{ route('materi-ajar.create') }}" class="px-6 py-3 bg-[#03045E] text-white rounded-full font-bold shadow-lg hover:scale-105 transition-all flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                UNGGAH MATERI
            </a>
        @endif
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-500 text-white rounded-2xl shadow-md font-bold flex items-center gap-2">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div class="mb-6 p-4 bg-red-500 text-white rounded-2xl shadow-md font-bold flex items-center gap-2">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-[2rem] shadow-sm p-6 border border-gray-100 overflow-x-auto">
        <table class="w-full border-separate border-spacing-y-3 min-w-[800px]">
            <thead>
                <tr class="text-white text-xs uppercase tracking-[0.1em] font-black">
                    <th class="bg-[#03045E] p-4 rounded-l-full text-left pl-6 w-1/3">Judul Materi</th>
                    <th class="bg-[#03045E] p-4 text-left">Mapel & Kelas</th>
                    <th class="bg-[#03045E] p-4 text-center">Tipe / Format</th>
                    <th class="bg-[#03045E] p-4 rounded-r-full text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-[#03045E] font-medium">
                @forelse($materiAjars as $materi)
                <tr class="bg-gray-50 hover:bg-white hover:shadow-md transition-all group">
                    <td class="p-4 rounded-l-2xl pl-6">
                        <div class="font-bold text-lg text-[#03045E]">{{ $materi->judul }}</div>
                        <div class="text-xs text-gray-500">{{ Str::limit($materi->deskripsi, 60) }}</div>
                        <div class="text-[10px] text-gray-400 mt-1 italic">Diunggah: {{ $materi->created_at->format('d M Y') }}</div>
                    </td>
                    <td class="p-4">
                        <div class="font-bold">{{ $materi->mapel->nama_mapel ?? '-' }}</div>
                        <div class="text-xs font-bold text-[#0077B6] bg-blue-50 inline-block px-2 py-1 rounded-md mt-1">
                            Kelas: {{ $materi->kelas->nama_kelas ?? '-' }}
                        </div>
                    </td>
                    <td class="p-4 text-center">
                        @if($materi->tipe_materi === 'File')
                            <span class="px-3 py-1 bg-amber-100 text-amber-600 rounded-full text-xs font-black uppercase flex items-center justify-center gap-1 w-max mx-auto">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                DOKUMEN
                            </span>
                        @else
                            <span class="px-3 py-1 bg-emerald-100 text-emerald-600 rounded-full text-xs font-black uppercase flex items-center justify-center gap-1 w-max mx-auto">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                                TAUTAN
                            </span>
                        @endif
                    </td>
                    <td class="p-4 rounded-r-2xl text-center">
                        <div class="flex justify-center gap-2">
                            @if($materi->tipe_materi === 'File')
                                <a href="{{ asset('storage/' . $materi->file_path) }}" target="_blank" class="px-3 py-2 bg-[#0077B6] text-white rounded-xl hover:bg-[#03045E] transition-all font-bold text-xs inline-block">Buka File</a>
                            @else
                                <a href="{{ $materi->url_link }}" target="_blank" class="px-3 py-2 bg-emerald-500 text-white rounded-xl hover:bg-emerald-700 transition-all font-bold text-xs inline-block">Kunjungi Link</a>
                            @endif
                            
                            @if(auth()->user()->hasRole('guru-mapel') && $materi->guru_id === auth()->id())
                                <a href="{{ route('materi-ajar.edit', $materi->id) }}" class="px-3 py-2 bg-amber-100 text-amber-600 rounded-xl hover:bg-amber-500 hover:text-white transition-all font-bold text-xs inline-block">Edit</a>
                                <form action="{{ route('materi-ajar.destroy', $materi->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus materi ini? File juga akan terhapus.');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="px-3 py-2 bg-red-100 text-red-600 rounded-xl hover:bg-red-500 hover:text-white transition-all font-bold text-xs">Hapus</button>
                                </form>
                            @elseif(auth()->user()->hasRole('admin-sekolah'))
                                <form action="{{ route('materi-ajar.destroy', $materi->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus materi ini? File juga akan terhapus.');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="px-3 py-2 bg-red-100 text-red-600 rounded-xl hover:bg-red-500 hover:text-white transition-all font-bold text-xs">Hapus (Admin)</button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="p-10 text-center text-gray-400 font-medium italic">Belum ada materi yang diunggah. Mulai lengkapi bank materi Anda!</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        <div class="mt-4">
            {{ $materiAjars->links() }}
        </div>
    </div>
</x-app-layout>
