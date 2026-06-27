<x-app-layout>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>

    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-4">
        <a href="{{ route('dashboard') }}" class="hover:text-[#03045E] font-medium">Dashboard</a>
        <span>›</span>
        <a href="{{ route('presensi.index') }}" class="hover:text-[#03045E] font-medium">Manajemen Presensi Siswa</a>
        <span>›</span>
        <span class="text-[#03045E] font-bold">Input Presensi Massal</span>
    </div>

    <!-- Tombol Kembali -->
    <a href="{{ route('presensi.index') }}" class="flex items-center gap-2 text-sm text-gray-500 hover:text-[#03045E] font-medium mb-4">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Kembali
    </a>

    <!-- Header -->
    <div class="mb-6">
        <h2 class="text-3xl font-black text-[#03045E] tracking-tight">Input Presensi Massal</h2>
        <p class="text-gray-500 text-sm mt-1">Silakan pilih Mata Pelajaran dan Kelas untuk mulai menginput data presensi hari ini.</p>
    </div>

        @if($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-400 text-red-700 rounded-2xl">
                <div class="flex items-center gap-2 mb-2">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M12 3a9 9 0 100 18A9 9 0 0012 3z"/>
                    </svg>
                    <span class="font-bold">Proses Presensi Dibatalkan, terdapat {{ $errors->count() }} kesalahan:</span>
                </div>
                <ul class="list-disc list-inside space-y-1 text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

    @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 border border-red-400 text-red-700 rounded-2xl">
            <span class="font-bold">{{ session('error') }}</span>
        </div>
    @endif

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

    <!-- SELECTOR FORM -->
    <div class="bg-white rounded-[2rem] shadow-sm p-6 border border-gray-100 mb-6">
        <form method="GET" action="{{ route('presensi.create') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-start">
            <div>
                <label class="block text-sm font-bold text-[#03045E] mb-2 uppercase tracking-widest">Tanggal</label>
                <input type="text" 
                    name="tanggal" 
                    id="tanggal"
                    value="{{ old('tanggal', request('tanggal')) }}"
                    class="w-full rounded-2xl border-gray-200 focus:ring-[#03045E] focus:border-[#03045E] p-4 bg-white"
                    placeholder="Pilih tanggal">

                    <script>
                        flatpickr("#tanggal", {
                            dateFormat: "Y-m-d",
                            locale: "id",
                            maxDate: null,
                            allowInput: true,
                            disableMobile: true, // Matikan kalender mobile default
                            altInput: true,
                            altFormat: "j F Y",
                    });
                    </script>                           
                    @error('tanggal') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-bold text-[#03045E] mb-2 uppercase tracking-widest">Mata Pelajaran</label>
                <select name="mapel_id" 
                    class="w-full rounded-2xl border-gray-200 focus:ring-[#03045E] focus:border-[#03045E] p-4 bg-white">
                    <option value="">-- Pilih Mata Pelajaran --</option>
                    @foreach($mapels as $m)
                        <option value="{{ $m->id }}" {{ ($selectedMapel && $selectedMapel->id == $m->id) ? 'selected' : '' }}>
                            {{ $m->nama_mapel }}
                        </option>
                    @endforeach
                </select>
                @error('mapel_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-sm font-bold text-[#03045E] mb-2 uppercase tracking-widest">Kelas Tujuan</label>
                <select name="kelas_id" 
                    class="w-full rounded-2xl border-gray-200 focus:ring-[#03045E] focus:border-[#03045E] p-4 bg-white">
                    <option value="">-- Pilih Kelas --</option>
                    @foreach($kelasList as $k)
                        <option value="{{ $k->id }}" {{ ($selectedKelas && $selectedKelas->id == $k->id) ? 'selected' : '' }}>
                            {{ $k->nama_kelas }}
                        </option>
                    @endforeach
                </select>
                @error('kelas_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-sm font-bold mb-2 uppercase tracking-widest invisible">
                    Aksi
                </label>
                <button type="submit" 
                    class="w-full px-6 py-4 bg-[#03045E] text-white rounded-2xl font-bold shadow-lg hover:scale-105 transition-all uppercase tracking-widest">
                    Tampilkan Siswa
                </button>
            </div>
        </form>
    </div>

    <!-- BULK INPUT FORM -->
    @if($selectedMapel && $selectedKelas)
        <div class="bg-white rounded-[2rem] shadow-sm overflow-hidden p-6 border border-gray-100">
            <div class="flex justify-between items-center mb-6 border-b border-gray-100 pb-4">
                <div>
                    <h3 class="text-xl font-black text-[#03045E]">
                        Daftar Siswa - Kelas {{ $selectedKelas->nama_kelas }}
                    </h3>
                    <p class="text-sm text-gray-500 mt-1">
                        {{ \Carbon\Carbon::parse($selectedTanggal)->translatedFormat('d F Y') }} 
                        • {{ $selectedMapel->nama_mapel }}
                    </p>
                </div>
                <span class="px-4 py-2 bg-[#03045E]/10 text-[#03045E] rounded-xl text-sm font-bold">
                    {{ count($siswaList) }} Siswa
                </span>
            </div>

            <div class="flex flex-wrap gap-3 mb-6">
                <div class="flex items-center gap-2 px-4 py-2 rounded-xl bg-emerald-50 border border-emerald-200">
                    <span class="w-6 h-6 flex items-center justify-center rounded-lg bg-emerald-500 text-white font-bold text-sm">H</span>
                    <span class="text-sm font-medium text-emerald-700">Hadir</span>
                </div>

                <div class="flex items-center gap-2 px-4 py-2 rounded-xl bg-amber-50 border border-amber-200">
                    <span class="w-6 h-6 flex items-center justify-center rounded-lg bg-amber-500 text-white font-bold text-sm">S</span>
                    <span class="text-sm font-medium text-amber-700">Sakit</span>
                </div>

                <div class="flex items-center gap-2 px-4 py-2 rounded-xl bg-blue-50 border border-blue-200">
                    <span class="w-6 h-6 flex items-center justify-center rounded-lg bg-blue-500 text-white font-bold text-sm">I</span>
                    <span class="text-sm font-medium text-blue-700">Izin</span>
                </div>

                <div class="flex items-center gap-2 px-4 py-2 rounded-xl bg-red-50 border border-red-200">
                    <span class="w-6 h-6 flex items-center justify-center rounded-lg bg-red-500 text-white font-bold text-sm">A</span>
                    <span class="text-sm font-medium text-red-700">Alpa</span>
                </div>

                <div class="flex items-center gap-2 px-4 py-2 rounded-xl bg-purple-50 border border-purple-200">
                    <span class="w-6 h-6 flex items-center justify-center rounded-lg bg-purple-500 text-white font-bold text-sm">D</span>
                    <span class="text-sm font-medium text-purple-700">Dispensasi</span>
                </div>
            </div>

            <form action="{{ route('presensi.store') }}" method="POST">
                @csrf
                <input type="hidden" name="tanggal" value="{{ $selectedTanggal }}">
                <input type="hidden" name="mapel_id" value="{{ $selectedMapel->id }}">
                <input type="hidden" name="kelas_id" value="{{ $selectedKelas->id }}">

                <div class="overflow-x-auto">
                    <table class="w-full border-separate border-spacing-y-3">
                        <thead>
                            <tr class="text-white text-sm uppercase tracking-widest">
                                <th class="bg-[#03045E] p-4 rounded-l-full text-left">NISN / Nama Siswa</th>
                                <th class="bg-[#03045E] p-4 text-center">Status Kehadiran</th>
                                <th class="bg-[#03045E] p-4 rounded-r-full text-left">Catatan</th>
                            </tr>
                        </thead>
                        <tbody class="text-[#03045E] font-medium">
                            @forelse($siswaList as $siswa)
                            <tr class="bg-gray-50 hover:bg-gray-100 transition-all">
                                <td class="p-4 rounded-l-2xl">
                                    <div class="font-bold">{{ $siswa->nama_lengkap }}</div>
                                    <div class="text-xs text-gray-500 font-normal">NISN: {{ $siswa->nisn }}</div>
                                </td>
                                <td class="p-4 text-center">
                                    <div class="flex justify-center gap-1.5 flex-wrap">
                                        <label class="cursor-pointer">
                                            <input type="radio" name="presensi[{{ $siswa->nisn }}][status]" value="H" 
                                                class="peer sr-only" {{ $siswa->current_status == 'H' ? 'checked' : '' }}>
                                            <span class="inline-block px-4 py-2 rounded-xl text-sm font-bold bg-white text-gray-400 border-2 border-transparent peer-checked:border-emerald-500 peer-checked:bg-emerald-500 peer-checked:text-white hover:bg-gray-200 transition-all shadow-sm min-w-[40px]">H</span>
                                        </label>
                                        <label class="cursor-pointer">
                                            <input type="radio" name="presensi[{{ $siswa->nisn }}][status]" value="S" 
                                                class="peer sr-only" {{ $siswa->current_status == 'S' ? 'checked' : '' }}>
                                            <span class="inline-block px-4 py-2 rounded-xl text-sm font-bold bg-white text-gray-400 border-2 border-transparent peer-checked:border-amber-500 peer-checked:bg-amber-500 peer-checked:text-white hover:bg-gray-200 transition-all shadow-sm min-w-[40px]">S</span>
                                        </label>
                                        <label class="cursor-pointer">
                                            <input type="radio" name="presensi[{{ $siswa->nisn }}][status]" value="I" 
                                                class="peer sr-only" {{ $siswa->current_status == 'I' ? 'checked' : '' }}>
                                            <span class="inline-block px-4 py-2 rounded-xl text-sm font-bold bg-white text-gray-400 border-2 border-transparent peer-checked:border-blue-500 peer-checked:bg-blue-500 peer-checked:text-white hover:bg-gray-200 transition-all shadow-sm min-w-[40px]">I</span>
                                        </label>
                                        <label class="cursor-pointer">
                                            <input type="radio" name="presensi[{{ $siswa->nisn }}][status]" value="A" 
                                                class="peer sr-only" {{ $siswa->current_status == 'A' ? 'checked' : '' }}>
                                            <span class="inline-block px-4 py-2 rounded-xl text-sm font-bold bg-white text-gray-400 border-2 border-transparent peer-checked:border-red-500 peer-checked:bg-red-500 peer-checked:text-white hover:bg-gray-200 transition-all shadow-sm min-w-[40px]">A</span>
                                        </label>
                                        <label class="cursor-pointer">
                                            <input type="radio" name="presensi[{{ $siswa->nisn }}][status]" value="D" 
                                                class="peer sr-only" {{ $siswa->current_status == 'D' ? 'checked' : '' }}>
                                            <span class="inline-block px-4 py-2 rounded-xl text-sm font-bold bg-white text-gray-400 border-2 border-transparent peer-checked:border-purple-500 peer-checked:bg-purple-500 peer-checked:text-white hover:bg-gray-200 transition-all shadow-sm min-w-[40px]">D</span>
                                        </label>
                                    </div>
                                </td>
                                <td class="p-4 rounded-r-2xl">
                                    <input type="text" 
                                        name="presensi[{{ $siswa->nisn }}][catatan]" 
                                        value="{{ $siswa->current_catatan }}" 
                                        placeholder="Opsional (misal: Sakit Demam)"
                                        class="w-full rounded-xl border-gray-200 focus:ring-[#03045E] focus:border-[#03045E] p-3 text-sm bg-white shadow-sm">
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="p-8 text-center text-gray-400">
                                    <div class="flex flex-col items-center gap-2">
                                        <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                        <span class="font-bold text-gray-400">Tidak ada siswa di kelas ini</span>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if(count($siswaList) > 0)
                <div class="flex justify-end pt-6 border-t border-gray-100 mt-4">
                    <button type="submit" 
                        class="px-10 py-4 bg-[#03045E] text-white rounded-2xl font-bold shadow-lg hover:scale-105 transition-all flex items-center gap-3 uppercase tracking-widest">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Simpan Data Presensi
                    </button>
                </div>
                @endif
            </form>
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
        setTimeout(tutupNotif, 5000);
    </script>
</x-app-layout>