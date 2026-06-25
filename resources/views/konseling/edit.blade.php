<x-app-layout>
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-4">
        <a href="{{ route('konseling.index') }}" class="hover:text-[#03045E] font-medium flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Jadwal Konseling
        </a>
        <span>›</span>
        <span class="text-[#03045E] font-bold">Edit Sesi</span>
    </div>

    <div class="mb-6">
        <h2 class="text-3xl font-black text-[#03045E] tracking-tight uppercase">Update Jadwal & Hasil Konseling</h2>
    </div>

    <div class="bg-white rounded-[2rem] shadow-sm p-8 border border-gray-100 max-w-2xl">
        <form action="{{ route('konseling.update', $konseling->id) }}" method="POST" class="space-y-6">
            @csrf @method('PUT')
            
            <div class="mb-6 p-4 bg-gray-50 rounded-xl border border-gray-100">
                <div class="text-xs font-bold text-gray-500 uppercase mb-1">Siswa Terjadwal</div>
                <div class="font-bold text-[#03045E] text-lg">{{ $konseling->siswa->nama_lengkap }} ({{ $konseling->siswa_nisn }})</div>
                <div class="text-sm text-gray-600">Kelas: {{ $konseling->siswa->kelas->nama_kelas ?? '-' }}</div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-[#03045E] mb-2 uppercase tracking-wide">Tanggal & Waktu</label>
                    <input type="datetime-local" name="tanggal" value="{{ \Carbon\Carbon::parse($konseling->tanggal)->format('Y-m-d\TH:i') }}" required class="w-full bg-gray-50 border border-gray-200 text-[#03045E] rounded-2xl p-4 focus:ring-4 focus:ring-[#03045E]/20 focus:border-[#03045E] transition-all font-medium">
                </div>
                <div>
                    <label class="block text-sm font-bold text-[#03045E] mb-2 uppercase tracking-wide">Status</label>
                    <select name="status" required class="w-full bg-gray-50 border border-gray-200 text-[#03045E] rounded-2xl p-4 focus:ring-4 focus:ring-[#03045E]/20 focus:border-[#03045E] transition-all font-medium">
                        <option value="Terjadwal" {{ $konseling->status == 'Terjadwal' ? 'selected' : '' }}>Terjadwal</option>
                        <option value="Selesai" {{ $konseling->status == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="Batal" {{ $konseling->status == 'Batal' ? 'selected' : '' }}>Batal</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-[#03045E] mb-2 uppercase tracking-wide">Jenis Layanan</label>
                    <select name="jenis_layanan" required class="w-full bg-gray-50 border border-gray-200 text-[#03045E] rounded-2xl p-4 focus:ring-4 focus:ring-[#03045E]/20 focus:border-[#03045E] transition-all font-medium">
                        <option value="Konseling Pribadi" {{ $konseling->jenis_layanan == 'Konseling Pribadi' ? 'selected' : '' }}>Konseling Pribadi</option>
                        <option value="Konseling Kelompok" {{ $konseling->jenis_layanan == 'Konseling Kelompok' ? 'selected' : '' }}>Konseling Kelompok</option>
                        <option value="Bimbingan Karir" {{ $konseling->jenis_layanan == 'Bimbingan Karir' ? 'selected' : '' }}>Bimbingan Karir</option>
                        <option value="Bimbingan Belajar" {{ $konseling->jenis_layanan == 'Bimbingan Belajar' ? 'selected' : '' }}>Bimbingan Belajar</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-[#03045E] mb-2 uppercase tracking-wide">Topik / Bahasan</label>
                    <input type="text" name="topik" value="{{ $konseling->topik }}" required class="w-full bg-gray-50 border border-gray-200 text-[#03045E] rounded-2xl p-4 focus:ring-4 focus:ring-[#03045E]/20 focus:border-[#03045E] transition-all font-medium">
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-[#03045E] mb-2 uppercase tracking-wide">Deskripsi Kasus</label>
                <textarea name="deskripsi_kasus" rows="4" class="w-full bg-gray-50 border border-gray-200 text-[#03045E] rounded-2xl p-4 focus:ring-4 focus:ring-[#03045E]/20 focus:border-[#03045E] transition-all font-medium">{{ $konseling->deskripsi_kasus }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-bold text-[#03045E] mb-2 uppercase tracking-wide">Tindak Lanjut / Hasil</label>
                <textarea name="tindak_lanjut" rows="4" class="w-full bg-gray-50 border border-gray-200 text-[#03045E] rounded-2xl p-4 focus:ring-4 focus:ring-[#03045E]/20 focus:border-[#03045E] transition-all font-medium">{{ $konseling->tindak_lanjut }}</textarea>
            </div>

            <div class="pt-4 flex gap-4">
                <button type="submit" class="px-8 py-4 bg-amber-500 text-white rounded-full font-bold shadow-lg hover:scale-105 transition-all">SIMPAN PERUBAHAN</button>
                <a href="{{ route('konseling.index') }}" class="px-8 py-4 bg-gray-100 text-gray-500 rounded-full font-bold hover:bg-gray-200 transition-all">BATAL</a>
            </div>
        </form>
    </div>
</x-app-layout>
