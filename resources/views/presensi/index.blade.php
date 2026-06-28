<x-app-layout>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>

    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-4">
        <a href="{{ route('dashboard') }}" class="hover:text-[#03045E] font-medium flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Dashboard
        </a>
        <span>›</span>
        <span class="text-[#03045E] font-bold">Manajemen Presensi Siswa</span>
    </div>

    <!-- Header -->
    <div class="mb-6 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
        <div>
            @if(auth()->user()->hasRole('orang-tua'))
                <h2 class="text-3xl font-black text-[#03045E] tracking-tight">Rekap Presensi Siswa</h2>
                <p class="text-gray-500 text-sm mt-1">Pantau kehadiran anak Anda per mata pelajaran.</p>
            @elseif(auth()->user()->hasRole('wali-kelas'))
                <h2 class="text-3xl font-black text-[#03045E] tracking-tight">Rekap Presensi Siswa</h2>
                <p class="text-gray-500 text-sm mt-1">Riwayat presensi siswa di kelas Anda.</p>
            @else
                <h2 class="text-3xl font-black text-[#03045E] tracking-tight">Manajemen Presensi Siswa</h2>
                <p class="text-gray-500 text-sm mt-1">Kelola dan monitor riwayat presensi siswa.</p>
            @endif
        </div>
        <div class="flex gap-3 flex-wrap">
            @if(auth()->user()->hasRole('guru-mapel'))
                <a href="{{ route('presensi.create') }}" class="px-6 py-3 bg-[#03045E] text-white rounded-full font-bold shadow-lg hover:scale-105 transition-all flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    INPUT PRESENSI
                </a>
            @endif
            @if(auth()->user()->hasRole('orang-tua') || auth()->user()->hasRole('wali-kelas'))
                <a href="{{ route('presensi.cetak') }}" target="_blank" class="px-6 py-3 bg-emerald-500 text-white rounded-full font-bold shadow-lg hover:scale-105 transition-all flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    CETAK LAPORAN
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

    <!-- ① Summary Card (Orang Tua) — INFOGRAFIS DULU -->
    @if(auth()->user()->hasRole('orang-tua'))
        @php
            $total      = $totalHadirBulan + $totalAbsenBulan;
            $persen     = $total > 0 ? round(($totalHadirBulan / $total) * 100) : 0;
            $persenBar  = min($persen, 100);
        @endphp
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
            <!-- Hadir -->
            <div class="bg-emerald-50 border border-emerald-200 p-5 rounded-2xl flex items-center gap-4 shadow-sm">
                <div class="w-12 h-12 bg-emerald-500 rounded-full flex items-center justify-center flex-shrink-0 shadow">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                </div>
                <div>
                    <div class="text-3xl font-black text-emerald-700">{{ $totalHadirBulan }}</div>
                    <div class="text-xs text-emerald-600 font-semibold uppercase tracking-wide">Hadir Bulan Ini</div>
                </div>
            </div>
            <!-- Tidak Hadir -->
            <div class="bg-red-50 border border-red-200 p-5 rounded-2xl flex items-center gap-4 shadow-sm">
                <div class="w-12 h-12 bg-red-500 rounded-full flex items-center justify-center flex-shrink-0 shadow">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                </div>
                <div>
                    <div class="text-3xl font-black text-red-700">{{ $totalAbsenBulan }}</div>
                    <div class="text-xs text-red-600 font-semibold uppercase tracking-wide">Tidak Hadir</div>
                </div>
            </div>
            <!-- Persentase -->
            <div class="bg-blue-50 border border-blue-200 p-5 rounded-2xl shadow-sm">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center flex-shrink-0 shadow">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </div>
                    <div>
                        <div class="text-3xl font-black text-blue-700">{{ $persen }}%</div>
                        <div class="text-xs text-blue-600 font-semibold uppercase tracking-wide">Kehadiran</div>
                    </div>
                </div>
                <!-- Progress bar -->
                <div class="w-full bg-blue-200 rounded-full h-2">
                    <div class="bg-blue-500 h-2 rounded-full transition-all duration-500" 
                        style="--progress: {{ $persenBar }}%; width: var(--progress);">
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- ② Filter — SETELAH INFOGRAFIS -->
    @if(auth()->user()->hasRole('orang-tua'))
        <form method="GET" action="{{ route('presensi.index') }}" class="bg-white rounded-2xl shadow-sm p-4 mb-6 flex flex-wrap gap-3 items-end">
            <!-- Bulan -->
            <div class="flex flex-col gap-1">
                <label class="text-xs font-bold text-gray-500 uppercase tracking-wide">Bulan</label>
                <select name="bulan" class="rounded-full border-gray-200 pl-4 pr-10 py-2.5 text-sm focus:ring-[#03045E] focus:border-[#03045E] shadow-sm">
                    @foreach($bulanList as $key => $name)
                        <option value="{{ $key }}" {{ request('bulan', $bulan) == $key ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <!-- Tahun -->
            <div class="flex flex-col gap-1">
                <label class="text-xs font-bold text-gray-500 uppercase tracking-wide">Tahun</label>
                <select name="tahun" class="rounded-full border-gray-200 pl-4 pr-10 py-2.5 text-sm focus:ring-[#03045E] focus:border-[#03045E] shadow-sm">
                    @for($i = date('Y') - 2; $i <= date('Y') + 1; $i++)
                        <option value="{{ $i }}" {{ request('tahun', $tahun) == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
            </div>
            <!-- Mata Pelajaran dengan search -->
            <div class="flex flex-col gap-1 relative" x-data="{ open: false, search: '', selected: '{{ request('mapel_id') }}', selectedLabel: '{{ $mapelList->firstWhere('id', request('mapel_id'))?->nama_mapel ?? 'Semua Mata Pelajaran' }}' }">
                <label class="text-xs font-bold text-gray-500 uppercase tracking-wide">Mata Pelajaran</label>
                <input type="hidden" name="mapel_id" :value="selected">
                <button type="button" @click="open = !open"
                    class="rounded-full border border-gray-200 pl-4 pr-10 py-2.5 text-sm bg-white text-left w-52 shadow-sm flex items-center justify-between gap-2 focus:outline-none focus:ring-2 focus:ring-[#03045E]">
                    <span class="truncate" x-text="selectedLabel"></span>
                    <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="open" @click.away="open = false" x-transition
                    class="absolute top-full mt-1 left-0 bg-white border border-gray-200 rounded-2xl shadow-xl z-50 w-64">
                    <!-- Search input -->
                    <div class="p-2">
                        <input type="text" x-model="search" placeholder="Cari mata pelajaran..."
                            class="w-full px-3 py-2 text-sm border border-gray-200 rounded-full focus:outline-none focus:ring-2 focus:ring-[#03045E]">
                    </div>
                    <ul class="max-h-48 overflow-y-auto pb-2">
                        <li>
                            <button type="button" @click="selected = ''; selectedLabel = 'Semua Mata Pelajaran'; open = false"
                                class="w-full text-left px-4 py-2 text-sm hover:bg-gray-100 transition-colors"
                                :class="selected === '' ? 'font-bold text-[#03045E]' : 'text-gray-700'">
                                Semua Mata Pelajaran
                            </button>
                        </li>
                        @foreach($mapelList as $mapel)
                            <li x-show="search === '' || '{{ strtolower($mapel->nama_mapel) }}'.includes(search.toLowerCase())">
                                <button type="button"
                                    @click="selected = '{{ $mapel->id }}'; selectedLabel = '{{ $mapel->nama_mapel }}'; open = false"
                                    class="w-full text-left px-4 py-2 text-sm hover:bg-gray-100 transition-colors"
                                    :class="selected == '{{ $mapel->id }}' ? 'font-bold text-[#03045E]' : 'text-gray-700'">
                                    {{ $mapel->nama_mapel }}
                                </button>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <!-- Actions -->
            <div class="flex gap-2">
                <button type="submit" class="px-5 py-2.5 bg-[#03045E] text-white rounded-full font-bold text-sm shadow hover:scale-105 transition-all">
                    Cari
                </button>
                @if(request('mapel_id') || (request('bulan') && request('bulan') != date('m')) || (request('tahun') && request('tahun') != date('Y')))
                    <a href="{{ route('presensi.index') }}" class="px-5 py-2.5 bg-gray-200 text-gray-700 rounded-full font-bold text-sm shadow-sm hover:scale-105 transition-all">
                        Reset
                    </a>
                @endif
            </div>
        </form>

    @elseif($isGrouped)
        {{-- Filter untuk Guru Mapel & Wali Kelas --}}
        <form method="GET" action="{{ route('presensi.index') }}" class="flex flex-wrap gap-3 mb-6">
            <select name="bulan" class="rounded-full border-gray-200 pl-6 pr-10 py-3 focus:ring-[#03045E] focus:border-[#03045E] shadow-sm">
                @foreach($bulanList as $key => $name)
                    <option value="{{ $key }}" {{ request('bulan', $bulan) == $key ? 'selected' : '' }}>{{ $name }}</option>
                @endforeach
            </select>
            <select name="tahun" class="rounded-full border-gray-200 pl-6 pr-10 py-3 focus:ring-[#03045E] focus:border-[#03045E] shadow-sm">
                @for($i = date('Y') - 2; $i <= date('Y') + 1; $i++)
                    <option value="{{ $i }}" {{ request('tahun', $tahun) == $i ? 'selected' : '' }}>{{ $i }}</option>
                @endfor
            </select>
            <select name="mapel_id" class="rounded-full border-gray-200 pl-6 pr-10 py-3 focus:ring-[#03045E] focus:border-[#03045E] shadow-sm">
                <option value="">Semua Mata Pelajaran</option>
                @foreach($mapelList as $mapel)
                    <option value="{{ $mapel->id }}" {{ request('mapel_id') == $mapel->id ? 'selected' : '' }}>{{ $mapel->nama_mapel }}</option>
                @endforeach
            </select>
            <button type="submit" class="px-6 py-3 bg-[#03045E] text-white rounded-full font-bold shadow-lg hover:scale-105 transition-all">Cari</button>
            @if(request()->hasAny(['mapel_id']) || request('bulan') != date('m') || request('tahun') != date('Y'))
                <a href="{{ route('presensi.index') }}" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-full font-bold shadow-sm hover:scale-105 transition-all">Reset</a>
            @endif
        </form>
    @endif

    <!-- Table -->
    <div class="bg-white rounded-[2rem] shadow-sm overflow-hidden p-6">
        <div class="overflow-x-auto">
            <table class="w-full border-separate border-spacing-y-3">
                <thead>
                    <tr class="text-white text-sm uppercase tracking-widest">
                        <th class="bg-[#03045E] p-4 rounded-l-full text-left">Tanggal</th>
                        @if($isGrouped)
                            <th class="bg-[#03045E] p-4 text-left">Mata Pelajaran & Kelas</th>
                            <th class="bg-[#03045E] p-4 text-center">Rekap Kehadiran</th>
                            <th class="bg-[#03045E] p-4 rounded-r-full text-center">Aksi</th>
                        @elseif(auth()->user()->hasRole('orang-tua'))
                            <th class="bg-[#03045E] p-4 text-left">Siswa</th>
                            <th class="bg-[#03045E] p-4 text-left">Mata Pelajaran & Kelas</th>
                            <th class="bg-[#03045E] p-4 text-center">Status</th>
                            <th class="bg-[#03045E] p-4 rounded-r-full text-center">Catatan</th>
                        @else
                            <th class="bg-[#03045E] p-4 text-left">Siswa</th>
                            <th class="bg-[#03045E] p-4 text-left">Mata Pelajaran & Kelas</th>
                            <th class="bg-[#03045E] p-4 text-center">Status</th>
                            <th class="bg-[#03045E] p-4 rounded-r-full text-center">Catatan</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="text-[#03045E] font-medium">
                    @forelse($presensis as $p)
                        <tr class="bg-gray-50 hover:bg-gray-100 transition-all">
                            <td class="p-4 rounded-l-2xl whitespace-nowrap font-bold">
                                {{ \Carbon\Carbon::parse($p->tanggal)->format('d M Y') }}
                            </td>

                            @if($isGrouped)
                                <td class="p-4">
                                    <div class="font-bold">{{ $p->mapel->nama_mapel ?? '-' }}</div>
                                    <div class="text-sm text-gray-500 font-normal">Kelas: {{ $p->kelas->nama_kelas ?? '-' }}</div>
                                </td>
                                <td class="p-4 text-center">
                                    <div class="flex gap-2 justify-center">
                                        <span class="px-3 py-1 bg-emerald-100 text-emerald-700 font-bold rounded-full text-xs">✓ {{ $p->total_hadir }}</span>
                                        <span class="px-3 py-1 bg-red-100 text-red-700 font-bold rounded-full text-xs">✕ {{ $p->total_absen }}</span>
                                    </div>
                                </td>
                                <td class="p-4 rounded-r-2xl text-center">
                                    <a href="{{ route('presensi.show', $p->tanggal . '_' . $p->mapel_id . '_' . $p->kelas_id) }}" class="px-4 py-2 bg-blue-100 text-blue-600 rounded-xl hover:bg-blue-600 hover:text-white transition-colors font-bold text-xs inline-flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        Detail
                                    </a>
                                </td>

                            @else
                                {{-- Orang tua & role lain: tampilkan nama siswa --}}
                                <td class="p-4">
                                    <div class="font-bold text-base">{{ $p->siswa->nama_lengkap ?? '-' }}</div>
                                    <div class="text-xs text-gray-400 font-normal">NISN: {{ $p->siswa_nisn }}</div>
                                </td>
                                <td class="p-4">
                                    <div class="font-bold">{{ $p->mapel->nama_mapel ?? '-' }}</div>
                                    <div class="text-sm text-gray-500 font-normal">Kelas: {{ $p->kelas->nama_kelas ?? '-' }}</div>
                                </td>
                                <td class="p-4 text-center">
                                    @if($p->status == 'H')
                                        <span class="bg-emerald-100 text-emerald-700 font-bold px-4 py-1 rounded-full text-xs uppercase">Hadir</span>
                                    @elseif($p->status == 'S')
                                        <span class="bg-amber-100 text-amber-700 font-bold px-4 py-1 rounded-full text-xs uppercase">Sakit</span>
                                    @elseif($p->status == 'I')
                                        <span class="bg-blue-100 text-blue-700 font-bold px-4 py-1 rounded-full text-xs uppercase">Izin</span>
                                    @elseif($p->status == 'A')
                                        <span class="bg-red-100 text-red-700 font-bold px-4 py-1 rounded-full text-xs uppercase">Alpa</span>
                                    @elseif($p->status == 'D')
                                        <span class="bg-purple-100 text-purple-700 font-bold px-4 py-1 rounded-full text-xs uppercase">Dispensasi</span>
                                    @endif
                                </td>
                                <td class="p-4 rounded-r-2xl text-center text-sm text-gray-500">
                                    {{ $p->catatan ?? '-' }}
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $isGrouped ? 4 : 5 }}" class="p-8 text-center text-gray-400">
                                <div class="flex flex-col items-center gap-2">
                                    <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                    <span class="font-bold text-gray-400">Tidak ada riwayat presensi ditemukan</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-6">
            {{ $presensis->links() }}
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
                    <h3 class="text-lg font-black text-[#03045E]">Hapus Data Presensi?</h3>
                    <p class="text-gray-500 text-sm mt-1">
                        Data presensi "<span id="nama-presensi" class="font-bold text-[#03045E]"></span>"
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

        function bukaModal(url, nama) {
            document.getElementById('nama-presensi').textContent = nama;
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
        document.getElementById('modal-hapus').addEventListener('click', function(e) {
            if (e.target === this) tutupModal();
        });
    </script>
</x-app-layout>
