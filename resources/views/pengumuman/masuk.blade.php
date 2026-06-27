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
        <span class="text-[#03045E] font-bold">Papan Pengumuman</span>
    </div>

    <!-- Header -->
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-black text-[#03045E] tracking-tight">Papan Pengumuman</h2>
            <p class="text-gray-500 text-sm mt-1">Seluruh informasi dan pengumuman masuk untuk Anda.</p>
        </div>
    </div>

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

    <!-- Search & Filter -->
    <form method="GET" action="{{ route('pengumuman.masuk') }}" class="flex gap-3 mb-6 flex-wrap">
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

        <button type="submit" class="px-6 py-3 bg-[#03045E] text-white rounded-full font-bold shadow-lg hover:scale-105 transition-all">
            Cari
        </button>

        @if(request('search'))
            <a href="{{ route('pengumuman.masuk') }}" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-full font-bold shadow-sm hover:scale-105 transition-all">
                Reset
            </a>
        @endif
    </form>

    <!-- Daftar Pengumuman -->
    <div class="space-y-6">
        @forelse($data_pengumuman as $pengumuman)
        <div class="bg-white rounded-[2rem] shadow-sm p-8 border border-gray-100 hover:shadow-md transition-all">
            <div class="flex justify-between items-start mb-4">
                <h3 class="text-xl font-black text-[#03045E]">{{ $pengumuman->judul }}</h3>
                <span class="px-4 py-1.5 bg-blue-50 text-[#03045E] rounded-full text-xs font-black uppercase tracking-widest">
                    {{ $pengumuman->created_at->format('d M Y') }}
                </span>
            </div>
            
            <p class="text-gray-600 font-medium leading-relaxed mb-6 whitespace-pre-line">
                {{ $pengumuman->konten }}
            </p>
            
            <!-- File Lampiran (Jika Ada) -->
            @if($pengumuman->file_lampiran)
                <div class="mb-4 p-3 bg-gray-50 rounded-2xl border border-gray-200 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V7l-5-5z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 2v5h5M9 13l3-3m0 0l3 3m-3-3v8"/>
                        </svg>
                        <span class="text-sm font-medium text-gray-700">{{ basename($pengumuman->file_lampiran) }}</span>
                    </div>
                    <a href="{{ asset('storage/' . $pengumuman->file_lampiran) }}" 
                       target="_blank" 
                       class="px-3 py-1 bg-[#03045E] text-white rounded-full text-xs font-bold hover:bg-blue-900 transition-all flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V7l-5-5z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 2v5h5M9 13l3-3m0 0l3 3m-3-3v8"/>
                        </svg>
                        Lihat File
                    </a>
                </div>
            @endif
            
            <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                <div class="w-8 h-8 rounded-full bg-[#03045E] flex items-center justify-center text-white text-xs font-bold">
                    {{ substr($pengumuman->user?->name, 0, 1) }}
                </div>
                <div>
                    <p class="text-xs font-bold text-[#03045E]">Dikirim oleh: {{ $pengumuman->user?->name }}</p>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Target: {{ str_replace('-', ' ', $pengumuman->target_type) }}</p>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-[2rem] shadow-sm p-12 text-center border border-gray-100">
            <div class="flex flex-col items-center gap-2">
                <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                <p class="text-gray-400 font-medium italic">Belum ada pengumuman untuk Anda.</p>
                @if(request('search'))
                    <p class="text-sm text-gray-400">Coba ubah kata kunci pencarian</p>
                    <a href="{{ route('pengumuman.masuk') }}" class="mt-2 px-4 py-2 bg-gray-100 text-gray-600 rounded-full text-sm font-bold hover:bg-gray-200 transition-all">Reset Pencarian</a>
                @endif
            </div>
        </div>
        @endforelse

        <div class="mt-8">
            {{ $data_pengumuman->links() }}
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

        function tutupNotifError() {
            const notif = document.getElementById('notif-error');
            if (notif) {
                notif.style.transition = 'opacity 0.5s';
                notif.style.opacity = '0';
                setTimeout(() => notif.remove(), 500);
            }
        }

        // Auto hilang setelah 5 detik
        setTimeout(tutupNotif, 5000);
        setTimeout(tutupNotifError, 5000);
    </script>
</x-app-layout>