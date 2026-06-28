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
        <span class="text-[#03045E] font-bold">Pilih Mata Pelajaran</span>
    </div>

    <!-- Header -->
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-black text-[#03045E] tracking-tight">Pilih Mata Pelajaran</h2>
            @if(auth()->user()->hasRole('wali-kelas'))
                <p class="text-gray-500 text-sm mt-1">Daftar mata pelajaran untuk kelas yang Anda ampu ({{ $kelas->nama_kelas ?? 'Belum ada' }}).</p>
            @else
                <p class="text-gray-500 text-sm mt-1">Silakan pilih mata pelajaran yang Anda ampu untuk melihat atau mengelola nilai.</p>
            @endif
        </div>
        <div class="flex gap-3">
            @if(auth()->user()->hasRole('guru-mapel'))
                <a href="{{ route('nilai.create') }}" class="px-6 py-3 bg-[#03045E] text-white rounded-full font-bold shadow-lg hover:scale-105 transition-all flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    INPUT NILAI
                </a>
            @endif
            @if(auth()->user()->hasRole('wali-kelas'))
                <a href="{{ route('nilai.cetak') }}" target="_blank" class="px-6 py-3 bg-emerald-500 text-white rounded-full font-bold shadow-lg hover:scale-105 transition-all flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    CETAK RAPOR
                </a>
            @endif
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

    @if(auth()->user()->hasRole('wali-kelas') && !$kelas)
        <div class="bg-red-50 border border-red-200 text-red-600 p-6 rounded-[2rem] text-center font-bold">
            Anda belum ditugaskan sebagai wali kelas untuk kelas manapun.
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($mapels as $mapel)
                <a href="{{ route('nilai.index', ['mapel_id' => $mapel->id]) }}" class="bg-white rounded-[2rem] shadow-sm p-6 hover:shadow-md hover:scale-105 transition-all group flex flex-col justify-between h-40 border border-transparent hover:border-[#03045E]">
                    <div>
                        <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mb-4 group-hover:bg-[#03045E] group-hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-[#03045E]">{{ $mapel->nama_mapel }}</h3>
                    </div>
                    <div class="flex justify-end text-[#03045E]">
                        <svg class="w-5 h-5 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                </a>
            @empty
                <div class="col-span-full bg-white rounded-[2rem] shadow-sm p-8 text-center text-gray-400">
                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                    Belum ada data mata pelajaran yang tersedia.
                </div>
            @endforelse
        </div>
    @endif

    <script>
        function tutupNotif(){
            const notif=document.getElementById('notif-sukses');
            if(notif){
                notif.style.transition='opacity .5s';
                notif.style.opacity='0';
                setTimeout(()=>notif.remove(),500);
            }
        }
        setTimeout(tutupNotif,5000);
    </script>
</x-app-layout>
