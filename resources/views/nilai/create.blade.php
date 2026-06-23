<x-app-layout>
    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-4">
        <a href="{{ route('dashboard') }}" class="hover:text-[#03045E] font-medium">Dashboard</a>
        <span>›</span>
        <a href="{{ route('nilai.index') }}" class="hover:text-[#03045E] font-medium">Nilai</a>
        <span>›</span>
        <span class="text-[#03045E] font-bold">Input Baru</span>
    </div>

    <div class="mb-6">
        <h2 class="text-3xl font-black text-[#03045E] tracking-tight uppercase">Input / Edit Nilai Massal</h2>
        <p class="text-gray-500">Pilih Tahun Ajaran, Semester, Mata Pelajaran, dan Kelas untuk menginput nilai baru atau mengedit nilai yang sudah ada.</p>
    </div>

    <!-- SELECTOR FORM -->
    <div class="bg-white rounded-[2rem] shadow-sm p-8 mb-6 border border-gray-100">
        <form method="GET" action="{{ route('nilai.create') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
            <div>
                <label class="block text-sm font-bold text-[#03045E] mb-2">Tahun Ajaran</label>
                <input type="text" name="tahun_ajaran" value="{{ $selectedTahun }}" required placeholder="Misal: 2025/2026" class="w-full rounded-2xl border-gray-100 bg-gray-50 p-4 focus:ring-[#03045E]">
            </div>
            <div>
                <label class="block text-sm font-bold text-[#03045E] mb-2">Semester</label>
                <select name="semester" required class="w-full rounded-2xl border-gray-100 bg-gray-50 p-4 focus:ring-[#03045E]">
                    <option value="Ganjil" {{ $selectedSemester == 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
                    <option value="Genap" {{ $selectedSemester == 'Genap' ? 'selected' : '' }}>Genap</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-bold text-[#03045E] mb-2">Mata Pelajaran (Diampu)</label>
                <select name="mapel_id" required class="w-full rounded-2xl border-gray-100 bg-gray-50 p-4 focus:ring-[#03045E]">
                    <option value="">-- Pilih Mapel --</option>
                    @foreach($mapels as $m)
                        <option value="{{ $m->id }}" {{ ($selectedMapel && $selectedMapel->id == $m->id) ? 'selected' : '' }}>{{ $m->nama_mapel }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-bold text-[#03045E] mb-2">Kelas Tujuan</label>
                <select name="kelas_id" required class="w-full rounded-2xl border-gray-100 bg-gray-50 p-4 focus:ring-[#03045E]">
                    <option value="">-- Pilih Kelas --</option>
                    @foreach($kelasList as $k)
                        <option value="{{ $k->id }}" {{ ($selectedKelas && $selectedKelas->id == $k->id) ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                    @endforeach
                </select>
            </div>
            <div>
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
                        <tr class="text-white text-xs uppercase font-black tracking-widest text-center">
                            <th class="bg-[#03045E] p-4 rounded-l-full text-left pl-8 w-1/3">NISN / Nama Siswa</th>
                            <th class="bg-[#03045E] p-4 w-24">Tugas</th>
                            <th class="bg-[#03045E] p-4 w-24">Kuis</th>
                            <th class="bg-[#03045E] p-4 w-24">UTS</th>
                            <th class="bg-[#03045E] p-4 w-24">UAS</th>
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
                                    <input type="number" name="nilai[{{ $siswa->nisn }}][tugas]" value="{{ $siswa->current_tugas }}" min="0" max="100" required class="w-full text-center rounded-xl border-gray-200 bg-white p-3 font-bold focus:ring-[#03045E] shadow-sm">
                                </td>
                                <td class="p-4">
                                    <input type="number" name="nilai[{{ $siswa->nisn }}][kuis]" value="{{ $siswa->current_kuis }}" min="0" max="100" required class="w-full text-center rounded-xl border-gray-200 bg-white p-3 font-bold focus:ring-[#03045E] shadow-sm">
                                </td>
                                <td class="p-4">
                                    <input type="number" name="nilai[{{ $siswa->nisn }}][uts]" value="{{ $siswa->current_uts }}" min="0" max="100" required class="w-full text-center rounded-xl border-gray-200 bg-white p-3 font-bold focus:ring-[#03045E] shadow-sm">
                                </td>
                                <td class="p-4">
                                    <input type="number" name="nilai[{{ $siswa->nisn }}][uas]" value="{{ $siswa->current_uas }}" min="0" max="100" required class="w-full text-center rounded-xl border-gray-200 bg-white p-3 font-bold focus:ring-[#03045E] shadow-sm">
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
