<x-app-layout>
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-4">
        <a href="{{ route('dashboard') }}" class="hover:text-[#03045E] font-medium flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Dashboard
        </a>
        <span>›</span>
        <span class="text-[#03045E] font-bold">Bank Materi Ajar</span>
    </div>

    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-black text-[#03045E] tracking-tight">Bank Materi Ajar Pribadi</h2>
            <p class="text-gray-500 text-sm mt-1">Kelola dokumen materi dan tautan pembelajaran Anda.</p>
        </div>
        @if(auth()->user()->hasRole('guru-mapel'))
            <a href="{{ route('materi-ajar.create') }}" class="px-6 py-3 bg-[#03045E] text-white rounded-full font-bold shadow-lg hover:scale-105 transition-all flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                UNGGAH MATERI
            </a>
        @endif
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
    
    @if(session('error'))
        <div id="notif-error" class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-2xl flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M12 3a9 9 0 100 18A9 9 0 0012 3z"/></svg>
                <span class="font-medium">{{ session('error') }}</span>
            </div>
            <button onclick="tutupNotifError()" class="text-red-700 hover:text-red-900 ml-4">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    @endif

    <div class="bg-white rounded-[2rem] shadow-sm p-6 border border-gray-100 overflow-x-auto">
        <table class="w-full border-separate border-spacing-y-3 min-w-[800px]">
            <thead>
                <tr class="text-white text-sm uppercase tracking-widest">
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
                                <button 
                                    type="button"
                                    data-url="{{ route('materi-ajar.destroy', $materi->id) }}"
                                    data-nama="{{ $materi->judul }}"
                                    onclick="bukaModal(this.dataset.url, this.dataset.nama)"
                                    class="px-3 py-2 bg-red-100 text-red-600 rounded-xl hover:bg-red-500 hover:text-white transition-all font-bold text-xs">
                                    Hapus
                                </button>
                            @elseif(auth()->user()->hasRole('admin-sekolah'))
                                <button 
                                    type="button"
                                    data-url="{{ route('materi-ajar.destroy', $materi->id) }}"
                                    data-nama="{{ $materi->judul }}"
                                    onclick="bukaModal(this.dataset.url, this.dataset.nama)"
                                    class="px-3 py-2 bg-red-100 text-red-600 rounded-xl hover:bg-red-500 hover:text-white transition-all font-bold text-xs">
                                    Hapus
                                </button>
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

    <!-- Modal Konfirmasi Hapus -->
    <div id="modal-hapus" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50">
        <div class="bg-white rounded-2xl shadow-xl p-8 max-w-sm w-full mx-4">
            <div class="flex flex-col items-center text-center gap-4">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </div>
                <div>
                    <h3 class="text-lg font-black text-[#03045E]">Hapus Materi?</h3>
                    <p class="text-gray-500 text-sm mt-1">Data Materi <span id="nama-materi" class="font-bold text-[#03045E]"></span> akan dihapus permanen dan tidak bisa dikembalikan.</p>
                </div>
                <div class="flex gap-3 w-full mt-2">
                    <button onclick="tutupModal()" class="flex-1 py-3 bg-navy text-white rounded-xl font-bold hover:bg-navy-200 transition-all">
                        Batal
                    </button>
                    <form id="form-hapus" method="POST" class="flex-1">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-full py-3 bg-red-600 text-white rounded-xl font-bold hover:bg-red-700 transition-all">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function bukaModal(url, nama) {
            document.getElementById('nama-materi').textContent = nama;
            document.getElementById('form-hapus').action = url;
            const modal = document.getElementById('modal-hapus');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function tutupModal() {
            const modal = document.getElementById('modal-hapus');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        // Tutup modal kalau klik di luar
        document.getElementById('modal-hapus').addEventListener('click', function(e) {
            if (e.target === this) tutupModal();
        });
    </script>

    <script>
        function tutupNotif() {
            const notif = document.getElementById('notif-sukses');
            if (notif) {
                notif.style.transition = 'opacity 0.5s';
                notif.style.opacity = '0';
                setTimeout(() => notif.remove(), 500);
            }
        }
        function tutupNotifError() {
            const notif = document.getElementById('notif-error');
            if (notif) {
                notif.style.transition = 'opacity 0.5s';
                notif.style.opacity = '0';
                setTimeout(() => notif.remove(), 500);
            }
        }
        setTimeout(tutupNotif, 5000);
        setTimeout(tutupNotifError, 5000);
    </script>
</x-app-layout>
