<x-app-layout>
    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-4">
        <a href="{{ route('dashboard') }}" class="hover:text-[#03045E] font-medium">Dashboard</a>
        <span>›</span>
        <a href="{{ route('presensi.index') }}" class="hover:text-[#03045E] font-medium">Presensi</a>
        <span>›</span>
        <span class="text-[#03045E] font-bold">Input Baru</span>
    </div>

    <!-- Header -->
    <div class="mb-6">
        <h2 class="text-3xl font-black text-[#03045E] tracking-tight uppercase">Input Presensi Massal</h2>
        <p class="text-gray-500">Silakan pilih Mata Pelajaran dan Kelas untuk mulai menginput data presensi hari ini.</p>
    </div>

    <!-- SELECTOR FORM -->
    <div class="bg-white rounded-[2rem] shadow-sm p-8 mb-6 border border-gray-100">
        <form method="GET" action="{{ route('presensi.create') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <div>
                <label class="block text-sm font-bold text-[#03045E] mb-2">Tanggal</label>
                <input type="date" name="tanggal" value="{{ $selectedTanggal }}" required class="w-full rounded-2xl border-gray-100 bg-gray-50 p-4 focus:ring-[#03045E]">
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
                <button type="submit" class="w-full px-6 py-4 bg-[#03045E] text-white rounded-2xl font-bold shadow-lg hover:scale-105 transition-all">TAMPILKAN SISWA</button>
            </div>
        </form>
    </div>

    <!-- BULK INPUT FORM (TAMPIL JIKA FILTER SUDAH DIPILIH) -->
    @if($selectedMapel && $selectedKelas)
        <div class="bg-white rounded-[2rem] shadow-sm p-8 border border-gray-100">
            <h3 class="text-xl font-black text-[#03045E] mb-6 border-b pb-4">
                Daftar Siswa - Kelas {{ $selectedKelas->nama_kelas }} 
                <span class="text-gray-500 font-normal text-sm ml-2">({{ \Carbon\Carbon::parse($selectedTanggal)->format('d F Y') }})</span>
            </h3>

            <form action="{{ route('presensi.store') }}" method="POST">
                @csrf
                <input type="hidden" name="tanggal" value="{{ $selectedTanggal }}">
                <input type="hidden" name="mapel_id" value="{{ $selectedMapel->id }}">
                <input type="hidden" name="kelas_id" value="{{ $selectedKelas->id }}">

                <table class="w-full border-separate border-spacing-y-3 mb-8">
                    <thead>
                        <tr class="text-white text-xs uppercase font-black tracking-widest">
                            <th class="bg-[#03045E] p-4 rounded-l-full text-left pl-8">NISN / Nama Siswa</th>
                            <th class="bg-[#03045E] p-4 text-center">Status Kehadiran</th>
                            <th class="bg-[#03045E] p-4 rounded-r-full text-left pr-8">Catatan Tambahan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($siswaList as $siswa)
                            <tr class="bg-gray-50 hover:bg-gray-100 transition-colors">
                                <td class="p-4 rounded-l-2xl pl-8">
                                    <div class="text-[#03045E] font-bold text-lg">{{ $siswa->nama_lengkap }}</div>
                                    <div class="text-sm text-gray-500 font-normal">NISN: {{ $siswa->nisn }}</div>
                                </td>
                                <td class="p-4 text-center">
                                    <div class="flex justify-center gap-2">
                                        <!-- H -->
                                        <label class="cursor-pointer">
                                            <input type="radio" name="presensi[{{ $siswa->nisn }}][status]" value="H" class="peer sr-only" {{ $siswa->current_status == 'H' ? 'checked' : '' }}>
                                            <span class="inline-block px-4 py-2 rounded-full text-sm font-bold bg-white text-gray-400 border-2 border-transparent peer-checked:border-emerald-500 peer-checked:bg-emerald-500 peer-checked:text-white hover:bg-gray-200 transition-all shadow-sm">H</span>
                                        </label>
                                        <!-- S -->
                                        <label class="cursor-pointer">
                                            <input type="radio" name="presensi[{{ $siswa->nisn }}][status]" value="S" class="peer sr-only" {{ $siswa->current_status == 'S' ? 'checked' : '' }}>
                                            <span class="inline-block px-4 py-2 rounded-full text-sm font-bold bg-white text-gray-400 border-2 border-transparent peer-checked:border-amber-500 peer-checked:bg-amber-500 peer-checked:text-white hover:bg-gray-200 transition-all shadow-sm">S</span>
                                        </label>
                                        <!-- I -->
                                        <label class="cursor-pointer">
                                            <input type="radio" name="presensi[{{ $siswa->nisn }}][status]" value="I" class="peer sr-only" {{ $siswa->current_status == 'I' ? 'checked' : '' }}>
                                            <span class="inline-block px-4 py-2 rounded-full text-sm font-bold bg-white text-gray-400 border-2 border-transparent peer-checked:border-blue-500 peer-checked:bg-blue-500 peer-checked:text-white hover:bg-gray-200 transition-all shadow-sm">I</span>
                                        </label>
                                        <!-- A -->
                                        <label class="cursor-pointer">
                                            <input type="radio" name="presensi[{{ $siswa->nisn }}][status]" value="A" class="peer sr-only" {{ $siswa->current_status == 'A' ? 'checked' : '' }}>
                                            <span class="inline-block px-4 py-2 rounded-full text-sm font-bold bg-white text-gray-400 border-2 border-transparent peer-checked:border-red-500 peer-checked:bg-red-500 peer-checked:text-white hover:bg-gray-200 transition-all shadow-sm">A</span>
                                        </label>
                                        <!-- D -->
                                        <label class="cursor-pointer">
                                            <input type="radio" name="presensi[{{ $siswa->nisn }}][status]" value="D" class="peer sr-only" {{ $siswa->current_status == 'D' ? 'checked' : '' }}>
                                            <span class="inline-block px-4 py-2 rounded-full text-sm font-bold bg-white text-gray-400 border-2 border-transparent peer-checked:border-purple-500 peer-checked:bg-purple-500 peer-checked:text-white hover:bg-gray-200 transition-all shadow-sm">D</span>
                                        </label>
                                    </div>
                                </td>
                                <td class="p-4 rounded-r-2xl pr-8">
                                    <input type="text" name="presensi[{{ $siswa->nisn }}][catatan]" value="{{ $siswa->current_catatan }}" placeholder="Opsional (misal: Sakit Demam)" class="w-full rounded-xl border-gray-200 bg-white p-3 text-sm focus:ring-[#03045E] shadow-sm">
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="p-10 text-center text-gray-400 font-medium">Tidak ada data siswa ditemukan di kelas ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                @if(count($siswaList) > 0)
                <div class="flex justify-end pt-4 border-t border-gray-100">
                    <button type="submit" class="px-10 py-4 bg-[#03045E] text-white rounded-full font-black text-lg shadow-xl hover:bg-opacity-90 transition-all flex items-center gap-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        SIMPAN DATA PRESENSI
                    </button>
                </div>
                @endif
            </form>
        </div>
    @endif
</x-app-layout>
