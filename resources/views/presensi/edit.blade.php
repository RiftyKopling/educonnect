<x-app-layout>


    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-4">
        <a href="{{ route('dashboard') }}" class="hover:text-[#03045E] font-medium">Dashboard</a>
        <span>›</span>
        <a href="{{ route('presensi.index') }}" class="hover:text-[#03045E] font-medium">Manajemen Presensi Siswa</a>
        <span>›</span>
        <a href="{{ route('presensi.show', $sessionKey) }}" class="hover:text-[#03045E] font-medium">Detail Sesi Presensi</a>
        <span>›</span>
        <span class="text-[#03045E] font-bold">Edit Status Presensi </span>
    </div>

    <!-- Tombol Kembali -->
    <a href="{{ route('presensi.show', $sessionKey) }}" class="flex items-center gap-2 text-sm text-gray-500 hover:text-[#03045E] font-medium mb-4">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Kembali
    </a>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-1">
            <div class="mb-8 bg-gray-50 p-6 rounded-2xl border border-gray-100">
                <h3 class="font-bold text-[#03045E] text-lg border-b pb-2 mb-4">Informasi Siswa</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Nama Siswa</p>
                        <p class="font-bold text-[#03045E] text-lg">{{ $presensi->siswa->nama_lengkap ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">NISN</p>
                        <p class="font-bold text-[#03045E] text-lg">{{ $presensi->siswa_nisn }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Mata Pelajaran</p>
                        <p class="font-bold text-[#03045E] text-lg">{{ $presensi->mapel->nama_mapel ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Tanggal</p>
                        <p class="font-bold text-[#03045E] text-lg">{{ \Carbon\Carbon::parse($presensi->tanggal)->format('d F Y') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2">
            <form action="{{ route('presensi.update', $presensi->id) }}" method="POST">
                @csrf @method('PUT')
                
                <div class="mb-6">
                    <label class="block text-sm font-bold text-[#03045E] mb-4">Status Kehadiran</label>
                    <div class="flex flex-wrap gap-4">
                        <!-- H -->
                        <label class="cursor-pointer">
                            <input type="radio" name="status" value="H" class="peer sr-only" {{ $presensi->status == 'H' ? 'checked' : '' }}>
                            <span class="inline-block px-6 py-3 rounded-xl font-bold bg-gray-100 text-gray-500 peer-checked:bg-emerald-500 peer-checked:text-white transition-all shadow-sm">Hadir (H)</span>
                        </label>
                        <!-- S -->
                        <label class="cursor-pointer">
                            <input type="radio" name="status" value="S" class="peer sr-only" {{ $presensi->status == 'S' ? 'checked' : '' }}>
                            <span class="inline-block px-6 py-3 rounded-xl font-bold bg-gray-100 text-gray-500 peer-checked:bg-amber-500 peer-checked:text-white transition-all shadow-sm">Sakit (S)</span>
                        </label>
                        <!-- I -->
                        <label class="cursor-pointer">
                            <input type="radio" name="status" value="I" class="peer sr-only" {{ $presensi->status == 'I' ? 'checked' : '' }}>
                            <span class="inline-block px-6 py-3 rounded-xl font-bold bg-gray-100 text-gray-500 peer-checked:bg-blue-500 peer-checked:text-white transition-all shadow-sm">Izin (I)</span>
                        </label>
                        <!-- A -->
                        <label class="cursor-pointer">
                            <input type="radio" name="status" value="A" class="peer sr-only" {{ $presensi->status == 'A' ? 'checked' : '' }}>
                            <span class="inline-block px-6 py-3 rounded-xl font-bold bg-gray-100 text-gray-500 peer-checked:bg-red-500 peer-checked:text-white transition-all shadow-sm">Alpa (A)</span>
                        </label>
                        <!-- D -->
                        <label class="cursor-pointer">
                            <input type="radio" name="status" value="D" class="peer sr-only" {{ $presensi->status == 'D' ? 'checked' : '' }}>
                            <span class="inline-block px-6 py-3 rounded-xl font-bold bg-gray-100 text-gray-500 peer-checked:bg-purple-500 peer-checked:text-white transition-all shadow-sm">Dispensasi (D)</span>
                        </label>
                    </div>
                </div>

                <div class="mb-8">
                    <label class="block text-sm font-bold text-[#03045E] mb-2">Catatan Tambahan (Opsional)</label>
                    <textarea name="catatan" rows="2" class="w-full rounded-2xl border-gray-200 bg-gray-50 p-4 focus:ring-[#03045E] shadow-sm" placeholder="Masukkan keterangan tambahan jika perlu (misal: Sakit Demam Berdarah)...">{{ $presensi->catatan }}</textarea>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                    <a href="{{ route('presensi.show', $sessionKey) }}" class="px-8 py-3 bg-gray-100 text-gray-600 rounded-full font-bold hover:bg-gray-200 transition-all">BATAL</a>
                    <button type="submit" class="px-8 py-3 bg-[#03045E] text-white rounded-full font-bold shadow-lg hover:scale-105 transition-all">SIMPAN PERUBAHAN</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
