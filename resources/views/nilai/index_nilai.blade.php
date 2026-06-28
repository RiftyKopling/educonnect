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
        <a href="{{ route('nilai.index') }}" class="hover:text-[#03045E] font-medium">Pilih Mata Pelajaran</a>
        
        @if(auth()->user()->hasRole('guru-mapel'))
            <span>›</span>
            <a href="{{ route('nilai.index', ['mapel_id' => $mapel->id]) }}" class="hover:text-[#03045E] font-medium">Pilih Kelas</a>
        @endif
        
        <span>›</span>
        <span class="text-[#03045E] font-bold">Daftar Nilai</span>
    </div>

    <!-- Header -->
    <div class="mb-6 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
        <div>
            <div class="flex items-center gap-3">
                @php
                    $backRoute = auth()->user()->hasRole('guru-mapel') 
                        ? route('nilai.index', ['mapel_id' => $mapel->id]) 
                        : route('nilai.index');
                @endphp
                <a href="{{ $backRoute }}" class="p-2 bg-gray-100 text-gray-500 rounded-full hover:bg-[#03045E] hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </a>
                <h2 class="text-3xl font-black text-[#03045E] tracking-tight">Daftar Nilai Siswa</h2>
            </div>
            <p class="text-gray-500 text-sm mt-1">Mapel: <span class="font-bold text-[#03045E]">{{ $mapel->nama_mapel }}</span> | Kelas: <span class="font-bold text-[#03045E]">{{ $kelas->nama_kelas }}</span></p>
        </div>
        
        <div class="flex gap-3">
            @if(auth()->user()->hasRole('guru-mapel'))
                <a href="{{ route('nilai.create') }}" class="px-6 py-3 bg-[#03045E] text-white rounded-full font-bold shadow-lg hover:scale-105 transition-all flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    INPUT NILAI
                </a>
            @endif
        </div>
    </div>

    <form method="GET" action="{{ route('nilai.index') }}" class="flex gap-3 mb-6 flex-wrap">
        <input type="hidden" name="mapel_id" value="{{ $mapel->id }}">
        <input type="hidden" name="kelas_id" value="{{ $kelas->id }}">

        <div class="flex-1 relative min-w-[200px]">
            <input type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari nama siswa atau NISN..."
                class="w-full rounded-full border-gray-200 pl-12 pr-4 py-3 focus:ring-[#03045E] focus:border-[#03045E] shadow-sm">
            <svg class="w-5 h-5 absolute left-4 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </div>

        {{-- Filter Tahun Ajaran --}}
        <select name="tahun_ajaran" class="rounded-full border-gray-200 px-6 py-3 focus:ring-[#03045E] focus:border-[#03045E] shadow-sm">
            <option value="">Semua Tahun Ajaran</option>
            @php
                $tahunSekarang = date('Y');
                $tahunMulai = $tahunSekarang - 5;
            @endphp
            @for($tahun = $tahunMulai; $tahun <= $tahunSekarang + 1; $tahun++)
                <option value="{{ $tahun }}/{{ $tahun+1 }}" {{ request('tahun_ajaran') == $tahun.'/'.($tahun+1) ? 'selected' : '' }}>
                    {{ $tahun }}/{{ $tahun+1 }}
                </option>
            @endfor
        </select>

        <button type="submit" class="px-6 py-3 bg-[#03045E] text-white rounded-full font-bold shadow-lg hover:scale-105 transition-all">
            Cari
        </button>

        @if(request()->filled('search') || request()->filled('tahun_ajaran'))
            <a href="{{ route('nilai.index', ['mapel_id' => $mapel->id, 'kelas_id' => $kelas->id]) }}"
            class="px-6 py-3 bg-gray-200 text-gray-700 rounded-full font-bold shadow-sm hover:scale-105 transition-all">
                Reset
            </a>
        @endif
    </form>

    <!-- Notifikasi Success -->
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
            <table class="w-full border-separate border-spacing-y-3 min-w-[800px]">
                <thead>
                    <tr class="text-white text-sm uppercase tracking-widest">
                        <th class="bg-[#03045E] p-4 rounded-l-full text-left pl-6">Siswa</th>
                        <th class="bg-[#03045E] p-4 text-center">Tahun Ajaran</th>
                        <th class="bg-[#03045E] p-4 text-center">Tugas</th>
                        <th class="bg-[#03045E] p-4 text-center">Kuis</th>
                        <th class="bg-[#03045E] p-4 text-center">UTS</th>
                        <th class="bg-[#03045E] p-4 text-center">UAS</th>
                        <th class="bg-[#03045E] p-4 rounded-r-full text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-[#03045E] font-medium">
                    @forelse($nilais as $n)
                    <tr class="bg-gray-50 hover:bg-white hover:shadow-md transition-all group">
                        <td class="p-4 rounded-l-2xl pl-6">
                            <div class="font-bold text-lg">{{ $n->siswa->nama_lengkap ?? '-' }}</div>
                            <div class="text-xs text-gray-500">NISN: {{ $n->siswa_nisn }}</div>
                        </td>
                        <td class="p-4 text-center">
                            <div class="text-sm font-bold text-gray-500">{{ $n->semester }} - {{ $n->tahun_ajaran }}</div>
                        </td>
                        <td class="p-4 text-center">
                            <span class="px-3 py-1 rounded-full font-bold text-xs {{ $n->tugas < 75 ? 'bg-red-100 text-red-600' : 'bg-emerald-100 text-emerald-600' }}">{{ $n->tugas }}</span>
                        </td>
                        <td class="p-4 text-center">
                            <span class="px-3 py-1 rounded-full font-bold text-xs {{ $n->kuis < 75 ? 'bg-red-100 text-red-600' : 'bg-emerald-100 text-emerald-600' }}">{{ $n->kuis }}</span>
                        </td>
                        <td class="p-4 text-center">
                            <span class="px-3 py-1 rounded-full font-bold text-xs {{ $n->uts < 75 ? 'bg-red-100 text-red-600' : 'bg-emerald-100 text-emerald-600' }}">{{ $n->uts }}</span>
                        </td>
                        <td class="p-4 text-center">
                            <span class="px-3 py-1 rounded-full font-bold text-xs {{ $n->uas < 75 ? 'bg-red-100 text-red-600' : 'bg-emerald-100 text-emerald-600' }}">{{ $n->uas }}</span>
                        </td>
                        <td class="p-4 rounded-r-2xl text-center">
                            <div class="flex justify-center gap-2">
                                @if(auth()->user()->hasRole('guru-mapel') || auth()->user()->hasRole('wali-kelas'))
                                    <a href="{{ route('nilai.edit', $n->id) }}" class="p-2 bg-amber-100 text-amber-600 rounded-xl hover:bg-amber-500 hover:text-white transition-all">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                @endif
                                @if(auth()->user()->hasRole('guru-mapel'))
                                    <button
                                        type="button"
                                        data-url="{{ route('nilai.destroy', $n->id) }}"
                                        data-siswa="{{ $n->siswa->nama_lengkap ?? 'Siswa' }}"
                                        data-mapel="{{ $n->mapel->nama_mapel ?? 'Mata Pelajaran' }}"
                                        onclick="bukaModalNilai(this.dataset.url, this.dataset.siswa, this.dataset.mapel)"
                                        class="p-2 bg-red-100 text-red-600 rounded-xl hover:bg-red-500 hover:text-white transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                    <td colspan="7" class="p-8 text-center text-gray-400">
                        <div class="flex flex-col items-center gap-2">
                                <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                            <span class="font-bold text-gray-400">
                                Tidak ada data nilai ditemukan
                            </span>
                        </div>
                    </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-6">
            {{ $nilais->links() }}
        </div>
    </div>

    <!-- Modal Konfirmasi Hapus Nilai -->
    <div id="modal-hapus-nilai" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50">
        <div class="bg-white rounded-2xl shadow-xl p-8 max-w-sm w-full mx-4">
            <div class="flex flex-col items-center text-center gap-4">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-black text-[#03045E]">Hapus Data Nilai?</h3>
                    <p class="text-gray-500 text-sm mt-1">
                        Data nilai untuk siswa <span id="nama-siswa" class="font-bold text-[#03045E]"></span>
                        pada mata pelajaran <span id="nama-mapel" class="font-bold text-[#03045E]"></span>
                        akan dihapus permanen dan tidak bisa dikembalikan.
                    </p>
                </div>
                <div class="flex gap-3 w-full mt-2">
                    <button onclick="tutupModalNilai()" class="flex-1 py-3 bg-[#03045E] text-white rounded-xl font-bold hover:bg-[#05086b] transition-all">
                        Batal
                    </button>
                    <form id="form-hapus-nilai" method="POST" class="flex-1">
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
        function bukaModalNilai(url, namaSiswa, namaMapel) {
            document.getElementById('nama-siswa').textContent = namaSiswa;
            document.getElementById('nama-mapel').textContent = namaMapel;
            document.getElementById('form-hapus-nilai').action = url;
            const modal = document.getElementById('modal-hapus-nilai');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function tutupModalNilai() {
            const modal = document.getElementById('modal-hapus-nilai');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        // Tutup modal kalau klik di luar
        document.getElementById('modal-hapus-nilai').addEventListener('click', function(e) {
            if (e.target === this) tutupModalNilai();
        });

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
