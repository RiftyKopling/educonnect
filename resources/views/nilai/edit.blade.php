<x-app-layout>
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-4">
        <a href="{{ route('dashboard') }}" class="hover:text-[#03045E] font-medium">Dashboard</a>
        <span>›</span>
        <a href="{{ route('nilai.index') }}" class="hover:text-[#03045E] font-medium">Riwayat Nilai</a>
        <span>›</span>
        <span class="text-[#03045E] font-bold">Edit Nilai</span>
    </div>

    <div class="mb-6">
        <h2 class="text-3xl font-black text-[#03045E] tracking-tight uppercase">Edit Nilai Siswa</h2>
        <p class="text-gray-500">Koreksi angka nilai tugas, kuis, UTS, atau UAS untuk satu siswa tertentu.</p>
    </div>

    <div class="bg-white rounded-[2rem] shadow-sm p-8 border border-gray-100">
        <div class="mb-8 bg-gray-50 p-6 rounded-2xl border border-gray-100">
            <h3 class="font-bold text-[#03045E] text-lg border-b pb-2 mb-4">Informasi Siswa & Akademik</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div>
                    <p class="text-sm text-gray-500">Nama Siswa</p>
                    <p class="font-bold text-[#03045E] text-lg">{{ $nilai->siswa->nama_lengkap ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">NISN</p>
                    <p class="font-bold text-[#03045E] text-lg">{{ $nilai->siswa_nisn }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Mata Pelajaran</p>
                    <p class="font-bold text-[#03045E] text-lg">{{ $nilai->mapel->nama_mapel ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Tahun & Semester</p>
                    <p class="font-bold text-[#03045E] text-lg">{{ $nilai->tahun_ajaran }} ({{ $nilai->semester }})</p>
                </div>
            </div>
        </div>

        <form action="{{ route('nilai.update', $nilai->id) }}" method="POST">
            @csrf @method('PUT')
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-8">
                <div>
                    <label class="block text-sm font-bold text-[#03045E] mb-2">Nilai Tugas</label>
                    <input type="number" name="tugas" value="{{ $nilai->tugas }}" min="0" max="100" required class="w-full text-center rounded-2xl border-gray-200 bg-white p-4 font-black text-2xl focus:ring-[#03045E] shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-bold text-[#03045E] mb-2">Nilai Kuis</label>
                    <input type="number" name="kuis" value="{{ $nilai->kuis }}" min="0" max="100" required class="w-full text-center rounded-2xl border-gray-200 bg-white p-4 font-black text-2xl focus:ring-[#03045E] shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-bold text-[#03045E] mb-2">Nilai UTS</label>
                    <input type="number" name="uts" value="{{ $nilai->uts }}" min="0" max="100" required class="w-full text-center rounded-2xl border-gray-200 bg-white p-4 font-black text-2xl focus:ring-[#03045E] shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-bold text-[#03045E] mb-2">Nilai UAS</label>
                    <input type="number" name="uas" value="{{ $nilai->uas }}" min="0" max="100" required class="w-full text-center rounded-2xl border-gray-200 bg-white p-4 font-black text-2xl focus:ring-[#03045E] shadow-sm">
                </div>
            </div>

            <div class="mb-8">
                <label class="block text-sm font-bold text-[#03045E] mb-2">Catatan Tambahan (Opsional)</label>
                <textarea name="catatan" rows="3" class="w-full rounded-2xl border-gray-200 bg-gray-50 p-4 focus:ring-[#03045E] shadow-sm" placeholder="Contoh: Remedial UTS sudah tuntas">{{ $nilai->catatan }}</textarea>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('nilai.index') }}" class="px-8 py-4 bg-gray-100 text-gray-600 rounded-full font-bold hover:bg-gray-200 transition-all">BATAL</a>
                <button type="submit" class="px-8 py-4 bg-[#03045E] text-white rounded-full font-bold shadow-lg hover:scale-105 transition-all">SIMPAN KOREKSI</button>
            </div>
        </form>
    </div>
</x-app-layout>
