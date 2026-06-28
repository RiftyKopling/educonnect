<x-app-layout>
    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-4">
        <a href="{{ route('dashboard') }}" class="hover:text-[#03045E] font-medium flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg> Dashboard
        </a>
        <span>›</span>
        <a href="{{ route('nilai.index') }}" class="hover:text-[#03045E] font-medium">Pilih Mata Pelajaran</a>
        <span>›</span>
        <span class="text-[#03045E] font-bold">Pilih Kelas</span>
    </div>

    <!-- Header -->
    <div class="mb-6 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
        <div>
            <div class="flex items-center gap-3">
                <a href="{{ route('nilai.index') }}" class="p-2 bg-gray-100 text-gray-500 rounded-full hover:bg-[#03045E] hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </a>
                <h2 class="text-3xl font-black text-[#03045E] tracking-tight">Pilih Kelas</h2>
            </div>
            <p class="text-gray-500 text-sm mt-1">Mata Pelajaran: <span class="font-bold text-[#03045E]">{{ $mapel->nama_mapel }}</span></p>
        </div>
        
        <div class="flex-1 max-w-sm relative">
            <input type="text" id="searchKelas" placeholder="Cari nama kelas..." class="w-full rounded-full border-gray-200 pl-12 pr-4 py-3 focus:ring-[#03045E] focus:border-[#03045E] shadow-sm" onkeyup="filterKelas()">
            <svg class="w-5 h-5 absolute left-4 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4" id="kelasContainer">
        @forelse($kelasList as $kelas)
            <a href="{{ route('nilai.index', ['mapel_id' => $mapel->id, 'kelas_id' => $kelas->id]) }}" class="kelas-card bg-white rounded-2xl shadow-sm p-6 hover:shadow-md hover:scale-105 transition-all group flex items-center gap-4 border border-transparent hover:border-[#03045E]" data-name="{{ strtolower($kelas->nama_kelas) }}">
                <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center flex-shrink-0 group-hover:bg-[#03045E] group-hover:text-white transition-colors">
                    <span class="font-black text-lg">{{ $kelas->tingkat }}</span>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-[#03045E]">{{ $kelas->nama_kelas }}</h3>
                    <p class="text-xs text-gray-400 font-medium">T.A {{ $kelas->tahun_ajaran }}</p>
                </div>
            </a>
        @empty
            <div class="col-span-full bg-white rounded-[2rem] shadow-sm p-8 text-center text-gray-400">
                Belum ada data kelas yang tersedia.
            </div>
        @endforelse
    </div>

    <script>
        function filterKelas() {
            let input = document.getElementById('searchKelas').value.toLowerCase();
            let cards = document.querySelectorAll('.kelas-card');
            cards.forEach(card => {
                if (card.dataset.name.includes(input)) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });
        }
    </script>
</x-app-layout>
