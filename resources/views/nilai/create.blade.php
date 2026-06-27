<x-app-layout>
    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-4">
        <a href="{{ route('dashboard') }}" class="hover:text-[#03045E] font-medium">Dashboard</a>
        <span>›</span>
        <a href="{{ route('nilai.index') }}" class="hover:text-[#03045E] font-medium">Input Nilai Akademik</a>
        <span>›</span>
        <span class="text-[#03045E] font-bold">Input Nilai Massal</span>
    </div>

    <!-- Tombol Kembali -->
    <a href="{{ route('nilai.index') }}" class="flex items-center gap-2 text-sm text-gray-500 hover:text-[#03045E] font-medium mb-4">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Kembali
    </a>

        <!-- Header -->
    <div class="mb-6">
        <h2 class="text-3xl font-black text-[#03045E] tracking-tight">Input Nilai Akademik</h2>
        <p class="text-gray-500 text-sm mt-1">Silakan pilih Tahun Ajaran, Semester, Mata Pelajaran yang Diampu dan Kelas Tujuan untuk mulai menginput data nilai.</p>
    </div>

        @if($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-400 text-red-700 rounded-2xl">
                <div class="flex items-center gap-2 mb-2">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M12 3a9 9 0 100 18A9 9 0 0012 3z"/>
                    </svg>
                    <span class="font-bold">Proses Tambah Nilai Dibatalkan, terdapat {{ $errors->count() }} kesalahan:</span>
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
    <div class="bg-white rounded-[2rem] shadow-sm p-8 mb-6 border border-gray-100">
        <form method="GET" action="{{ route('nilai.create') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4 items-start">
            <div>
                <label class="block text-sm font-bold text-[#03045E] mb-2">Tahun Ajaran</label>
                <input type="text" name="tahun_ajaran" value="{{ $selectedTahun }}" placeholder="Misal: 2025/2026" class="w-full rounded-2xl border-gray-100 bg-gray-50 p-4 focus:ring-[#03045E]">
            </div>
            <div>
                <label class="block text-sm font-bold text-[#03045E] mb-2">Semester</label>
                <select name="semester" class="w-full rounded-2xl border-gray-100 bg-gray-50 p-4 focus:ring-[#03045E]">
                    <option value="Ganjil" {{ $selectedSemester == 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
                    <option value="Genap" {{ $selectedSemester == 'Genap' ? 'selected' : '' }}>Genap</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-bold text-[#03045E] mb-2">Mata Pelajaran (Diampu)</label>
                <select name="mapel_id" class="w-full rounded-2xl border-gray-100 bg-gray-50 p-4 focus:ring-[#03045E]">
                    <option value="">-- Pilih Mapel --</option>
                    @foreach($mapels as $m)
                        <option value="{{ $m->id }}" {{ ($selectedMapel && $selectedMapel->id == $m->id) ? 'selected' : '' }}>{{ $m->nama_mapel }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-bold text-[#03045E] mb-2">Kelas Tujuan</label>
                <select name="kelas_id" class="w-full rounded-2xl border-gray-100 bg-gray-50 p-4 focus:ring-[#03045E]">
                    <option value="">-- Pilih Kelas --</option>
                    @foreach($kelasList as $k)
                        <option value="{{ $k->id }}" {{ ($selectedKelas && $selectedKelas->id == $k->id) ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-bold text-[#03045E] mb-2 invisible">Tahun Ajaran</label>
                <button type="submit" class="w-full px-6 py-4 bg-[#03045E] text-white rounded-2xl font-bold shadow-lg hover:scale-105 transition-all">TAMPILKAN</button>
            </div>
        </form>
    </div>

    <!-- BULK INPUT FORM -->
    @if($selectedMapel && $selectedKelas)
        <div class="bg-white rounded-[2rem] shadow-sm p-8 border border-gray-100 overflow-x-auto">
            <h3 class="text-xl font-black text-[#03045E] mb-6 border-b pb-4">
                Daftar Siswa - Kelas {{ $selectedKelas->nama_kelas }} 
                <span class="text-gray-500 font-normal text-sm ml-2">({{ $selectedSemester }} - {{ $selectedTahun }})</span>
            </h3>

            <form action="{{ route('nilai.store') }}" method="POST">
                @csrf
                <input type="hidden" name="semester" value="{{ $selectedSemester }}">
                <input type="hidden" name="tahun_ajaran" value="{{ $selectedTahun }}">
                <input type="hidden" name="mapel_id" value="{{ $selectedMapel->id }}">
                <input type="hidden" name="kelas_id" value="{{ $selectedKelas->id }}">

                <table class="w-full border-separate border-spacing-y-3 mb-8 min-w-[900px]">
                    <thead>
                        <tr class="text-white text-sm uppercase tracking-widest">
                            <th class="bg-[#03045E] p-4 rounded-l-full text-left pl-8 w-1/3">NISN / Nama Siswa</th>
                            <th class="bg-[#03045E] p-4 w-32">Tugas</th>
                            <th class="bg-[#03045E] p-4 w-32">Kuis</th>
                            <th class="bg-[#03045E] p-4 w-32">UTS</th>
                            <th class="bg-[#03045E] p-4 w-32">UAS</th>
                            <th class="bg-[#03045E] p-4 rounded-r-full text-left pl-6">Catatan Tambahan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($siswaList as $siswa)
                            <tr class="bg-gray-50 hover:bg-gray-100 transition-colors">
                                <td class="p-4 rounded-l-2xl pl-8">
                                    <div class="text-[#03045E] font-bold text-lg">{{ $siswa->nama_lengkap }}</div>
                                    <div class="text-sm text-gray-500 font-normal">NISN: {{ $siswa->nisn }}</div>
                                </td>
                                <td class="p-4">
                                    <input type="number" 
                                        name="nilai[{{ $siswa->nisn }}][tugas]" 
                                        placeholder="0"
                                        value="{{ $siswa->current_tugas !== 0 ? $siswa->current_tugas : '' }}" 
                                        min="0" max="100" 
                                        onfocus="this.select()"
                                        class="w-full text-center rounded-xl border-gray-200 bg-white p-3 font-bold focus:ring-[#03045E] shadow-sm">
                                </td>
                                <td class="p-4">
                                    <input type="number" 
                                        name="nilai[{{ $siswa->nisn }}][kuis]" 
                                        placeholder="0"
                                        value="{{ $siswa->current_kuis !== 0 ? $siswa->current_kuis : '' }}" 
                                        min="0" max="100" 
                                        onfocus="this.select()"
                                        class="w-full text-center rounded-xl border-gray-200 bg-white p-3 font-bold focus:ring-[#03045E] shadow-sm">
                                </td>
                                <td class="p-4">
                                    <input type="number" 
                                        name="nilai[{{ $siswa->nisn }}][uts]" 
                                        placeholder="0"
                                        value="{{ $siswa->current_uts !== 0 ? $siswa->current_uts : '' }}" 
                                        min="0" max="100" 
                                        onfocus="this.select()"
                                        class="w-full text-center rounded-xl border-gray-200 bg-white p-3 font-bold focus:ring-[#03045E] shadow-sm">
                                </td>
                                <td class="p-4">
                                    <input type="number" 
                                        name="nilai[{{ $siswa->nisn }}][uas]" 
                                        placeholder="0"
                                        value="{{ $siswa->current_uas !== 0 ? $siswa->current_uas : '' }}" 
                                        min="0" max="100" 
                                        onfocus="this.select()"
                                        class="w-full text-center rounded-xl border-gray-200 bg-white p-3 font-bold focus:ring-[#03045E] shadow-sm">
                                </td>
                                <td class="p-4 rounded-r-2xl pl-4 pr-6">
                                    <input type="text" name="nilai[{{ $siswa->nisn }}][catatan]" value="{{ $siswa->current_catatan }}" placeholder="Opsional" class="w-full rounded-xl border-gray-200 bg-white p-3 text-sm focus:ring-[#03045E] shadow-sm">
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-10 text-center text-gray-400 font-medium">Tidak ada data siswa ditemukan di kelas ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                @if(count($siswaList) > 0)
                <div class="flex justify-end pt-4 border-t border-gray-100">
                    <button type="submit" class="px-10 py-4 bg-[#03045E] text-white rounded-full font-black text-lg shadow-xl hover:bg-opacity-90 transition-all flex items-center gap-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        SIMPAN DATA NILAI
                    </button>
                </div>
                @endif
            </form>
        </div>
    @endif
</x-app-layout>
