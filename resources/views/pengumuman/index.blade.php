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
        <span class="text-[#03045E] font-bold">Manajemen Pengumuman</span>
    </div>

    <!-- Header -->
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-black text-[#03045E] tracking-tight">Manajemen Pengumuman</h2>
            <p class="text-gray-500">Mengelola pengumuman yang telah terkirim secara terpusat.</p>
        </div>

        @if(auth()->user()->role?->slug !== 'orang-tua')
        <a href="{{ route('pengumuman.create') }}" class="px-6 py-3 bg-[#03045E] text-white rounded-full font-bold shadow-lg hover:scale-105 transition-all flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            BUAT PENGUMUMAN
        </a>
        @endif
    </div>

    <!-- Search & Filter -->
    <form method="GET" action="{{ route('pengumuman.index') }}" class="flex gap-3 mb-6 flex-wrap">
        <div class="flex-1 relative min-w-[200px]">
            <input type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari judul pengumuman..."
                class="w-full rounded-full border-gray-200 pl-12 pr-4 py-3 focus:ring-[#03045E] focus:border-[#03045E] shadow-sm">
            <svg class="w-5 h-5 absolute left-4 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </div>

        <!-- Tampilkan filter target hanya untuk role tertentu -->
        @php $role = auth()->user()->role?->slug; @endphp
        
        @if(in_array($role, ['kepala-sekolah', 'wali-kelas', 'guru-mapel', 'guru-bk']))
        <select name="target" class="rounded-full border-gray-200 px-6 py-3 focus:ring-[#03045E] focus:border-[#03045E] shadow-sm">
            <option value="">Semua Target</option>
            <option value="all" {{ request('target') == 'all' ? 'selected' : '' }}>Semua Pengguna</option>
            <option value="all-parents" {{ request('target') == 'all-parents' ? 'selected' : '' }}>Semua Orang Tua</option>
            <option value="class-parents" {{ request('target') == 'class-parents' ? 'selected' : '' }}>Orang Tua Kelas</option>
            <option value="kepala-sekolah" {{ request('target') == 'kepala-sekolah' ? 'selected' : '' }}>Kepala Sekolah</option>
        </select>
        @endif

        <button type="submit" class="px-6 py-3 bg-[#03045E] text-white rounded-full font-bold shadow-lg hover:scale-105 transition-all">
            Cari
        </button>

        @if(request('search') || request('target'))
            <a href="{{ route('pengumuman.index') }}" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-full font-bold shadow-sm hover:scale-105 transition-all">
                Reset
            </a>
        @endif
    </form>

    <!-- Notifikasi Success -->
    @if(session('success'))
        <div id="notif-sukses" class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-2xl flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
            <button onclick="tutupNotif()" class="text-green-700 hover:text-green-900 ml-4">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    @endif

    <!-- Notifikasi Error -->
    @if(session('error'))
        <div id="notif-error" class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-2xl flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M12 3a9 9 0 100 18A9 9 0 0012 3z"/>
                </svg>
                <span class="font-medium">{{ session('error') }}</span>
            </div>
            <button onclick="tutupNotifError()" class="text-red-700 hover:text-red-900 ml-4">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    @endif

    <!-- Notifikasi Error Modal (untuk akses ditolak) -->
    @if(session('error_modal'))
        <div id="error-modal-container" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
            <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full mx-4 transform transition-all duration-300 scale-100">
                <div class="flex flex-col items-center text-center gap-4">
                    <!-- Icon Error -->
                    <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center animate-pulse">
                        <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    
                    <div>
                        <h3 class="text-2xl font-black text-[#03045E]">Akses Ditolak</h3>
                        <p class="text-gray-600 text-sm mt-2 leading-relaxed">
                            {{ session('error_modal') }}
                        </p>
                    </div>

                    <!-- Detail tambahan -->
                    <div class="w-full bg-gray-50 rounded-xl p-4 text-left text-sm text-gray-600">
                        <div class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-[#03045E] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div>
                                <p class="font-medium text-[#03045E]">Informasi:</p>
                                <p class="text-xs">Hanya pembuat pengumuman atau Kepala Sekolah yang dapat mengelola pengumuman ini.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Kembali -->
                    <button onclick="tutupErrorModal(); setTimeout(redirectToIndex, 300);" 
                            class="w-full py-3 bg-[#03045E] text-white rounded-xl font-bold hover:bg-[#05086b] transition-all transform hover:scale-[1.02] shadow-lg">
                        Kembali ke Manajemen Pengumuman
                    </button>
                </div>
            </div>
        </div>
    @endif

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

        // Fungsi untuk error modal
        function tutupErrorModal(redirect = true) {
            const modal = document.getElementById('error-modal-container');
            if (modal) {
                modal.style.transition = 'opacity 0.3s ease';
                modal.style.opacity = '0';
                setTimeout(() => {
                    modal.style.display = 'none';
                    modal.remove(); // Bersihkan DOM
                    
                    // Redirect hanya jika parameter true
                    if (redirect) {
                        window.location.href = "{{ route('pengumuman.index') }}";
                    }
                }, 300);
            }
        }

        // Setup modal saat DOM siap
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('error-modal-container');
            if (modal) {
                // Tutup dengan klik di luar modal
                modal.addEventListener('click', function(e) {
                    if (e.target === this) {
                        tutupErrorModal(true);
                    }
                });

                // Auto close setelah 8 detik (lebih baik dari 10)
                setTimeout(function() {
                    if (document.getElementById('error-modal-container')) {
                        tutupErrorModal(true);
                    }
                }, 8000);
            }

            // Auto hilang notifikasi setelah 5 detik
            setTimeout(tutupNotif, 5000);
            setTimeout(tutupNotifError, 5000);
        });
    </script>

    <div class="bg-white rounded-[2rem] shadow-sm overflow-hidden p-6">
        <table class="w-full border-separate border-spacing-y-3">
            <thead>
                <tr class="text-white text-sm uppercase tracking-widest">
                    <th class="bg-[#03045E] p-4 rounded-l-full text-left">Judul</th>
                    <th class="bg-[#03045E] p-4 text-left">Target</th>
                    <th class="bg-[#03045E] p-4 text-left">
                        <a href="{{ route('pengumuman.index', array_merge(request()->query(), [
                            'sort' => 'created_at',
                            'direction' => request()->input('sort') == 'created_at' && request()->input('direction') == 'asc' ? 'desc' : 'asc'
                        ])) }}"
                           class="flex items-center gap-1 hover:text-gray-300 transition-colors">
                            Tanggal
                            @if(request()->input('sort') == 'created_at' || !request()->input('sort'))
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    @if(request()->input('direction') == 'asc')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                                    @else
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    @endif
                                </svg>
                            @endif
                        </a>
                    </th>
                    <th class="bg-[#03045E] p-4 text-left">Lampiran</th>
                    <th class="bg-[#03045E] p-4 rounded-r-full text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-[#03045E] font-medium">
                @forelse($data_pengumuman as $p)
                <tr class="bg-gray-50 hover:bg-gray-100 transition-all">
                    <td class="p-4 rounded-l-2xl font-bold">{{ $p->judul }}</td>
                    <td class="p-4">
                        <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-bold uppercase">
                            {{ str_replace('-', ' ', $p->target_type) }}
                        </span>
                    </td>
                    <td class="p-4 text-sm text-gray-600">
                        {{ $p->created_at->format('d M Y H:i') }}
                    </td>
                    <td class="p-4">
                        @if($p->file_lampiran)
                            <a href="{{ asset('storage/' . $p->file_lampiran) }}" 
                            target="_blank" 
                            class="inline-flex items-center gap-1 px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-bold hover:bg-green-200 transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V7l-5-5z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 2v5h5M9 13l3-3m0 0l3 3m-3-3v8"/>
                                </svg>
                                Lihat
                            </a>
                        @else
                            <span class="text-xs text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="p-4 rounded-r-2xl text-center">
                        <div class="flex justify-center gap-2">
                            @if(auth()->user()->role?->slug !== 'orang-tua')
                                <a href="{{ route('pengumuman.edit', $p->id) }}" class="p-2 bg-amber-100 text-amber-600 rounded-xl hover:bg-amber-200 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <button
                                    type="button"
                                    data-url="{{ route('pengumuman.destroy', $p->id) }}"
                                    data-nama="{{ $p->judul }}"
                                    onclick="bukaModal(this.dataset.url, this.dataset.nama)"
                                    class="p-2 bg-red-100 text-red-600 rounded-xl hover:bg-red-200 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            @else
                                <span class="text-xs text-gray-400 italic">Hanya bisa melihat</span>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-8 text-center text-gray-400">
                        <div class="flex flex-col items-center gap-2">
                            <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            <span class="font-bold text-gray-400">Tidak ada pengumuman ditemukan</span>
                            @if(request('search') || request('target'))
                                <span class="text-sm text-gray-400">Coba ubah kata kunci pencarian atau filter target</span>
                                <a href="{{ route('pengumuman.index') }}" class="mt-2 px-4 py-2 bg-gray-100 text-gray-600 rounded-full text-sm font-bold hover:bg-gray-200 transition-all">Reset Pencarian</a>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-6">
            {{ $data_pengumuman->links() }}
        </div>
    </div>

    <!-- Modal Konfirmasi Hapus -->
    <div id="modal-hapus" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50">
        <div class="bg-white rounded-2xl shadow-xl p-8 max-w-sm w-full mx-4">
            <div class="flex flex-col items-center text-center gap-4">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-black text-[#03045E]">Hapus Pengumuman?</h3>
                    <p class="text-gray-500 text-sm mt-1">
                        Pengumuman "<span id="nama-pengumuman" class="font-bold text-[#03045E]"></span>"
                        akan dihapus permanen dan tidak bisa dikembalikan.
                    </p>
                </div>
                <div class="flex gap-3 w-full mt-2">
                    <button onclick="tutupModal()" class="flex-1 py-3 bg-[#03045E] text-white rounded-xl font-bold hover:bg-[#05086b] transition-all">
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
            document.getElementById('nama-pengumuman').textContent = nama;
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

</x-app-layout>