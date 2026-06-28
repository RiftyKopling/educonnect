<x-app-layout>
    <div class="max-w-3xl mx-auto">
        <div class="mb-8">
            <div class="flex items-center gap-2 text-sm text-gray-500 mb-2">
                <a href="{{ route('dashboard') }}" class="hover:text-[#03045E] font-medium">Dashboard</a>
                <span>›</span>
                <a href="{{ route('konseling.index') }}" class="hover:text-[#03045E] font-medium">Jadwal Konseling</a>
                <span>›</span>
                <span class="text-[#03045E] font-bold">Buat Jadwal</span>
            </div>
            <a href="{{ route('konseling.index') }}" class="flex items-center gap-2 text-sm text-gray-500 hover:text-[#03045E] font-medium mb-4">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali
            </a>
            <h2 class="text-3xl font-black text-[#03045E] tracking-tight">Buat Jadwal Konseling</h2>
            <p class="text-gray-500 text-sm mt-1">Tambahkan jadwal sesi bimbingan dan konseling siswa.</p>
        </div>

        <div class="bg-white rounded-[2rem] shadow-sm p-8 border border-gray-100">
            <form action="{{ route('konseling.store') }}" method="POST" class="space-y-6">
                @csrf

                @if($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border border-red-400 text-red-700 rounded-2xl">
                        <div class="flex items-center gap-2 mb-2">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M12 3a9 9 0 100 18A9 9 0 0012 3z"/></svg>
                            <span class="font-bold">Terdapat {{ $errors->count() }} kesalahan:</span>
                        </div>
                        <ul class="list-disc list-inside space-y-1 text-sm">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-[#03045E] mb-2 uppercase tracking-widest">Tanggal & Waktu</label>
                        <input type="datetime-local" name="tanggal" required value="{{ old('tanggal') }}"
                            class="w-full bg-gray-50 border border-gray-200 text-[#03045E] rounded-2xl p-4 focus:ring-4 focus:ring-[#03045E]/20 focus:border-[#03045E] transition-all font-medium">
                        @error('tanggal') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-[#03045E] mb-2 uppercase tracking-widest">Status</label>
                        <select name="status" required class="w-full bg-gray-50 border border-gray-200 text-[#03045E] rounded-2xl p-4 focus:ring-4 focus:ring-[#03045E]/20 focus:border-[#03045E] transition-all font-medium">
                            <option value="Terjadwal" {{ old('status') == 'Terjadwal' ? 'selected' : '' }}>Terjadwal</option>
                            <option value="Selesai" {{ old('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="Batal" {{ old('status') == 'Batal' ? 'selected' : '' }}>Batal</option>
                        </select>
                        @error('status') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-[#03045E] mb-2 uppercase tracking-widest">Pilih Kelas</label>
                    <select id="kelas_id" class="w-full bg-gray-50 border border-gray-200 text-[#03045E] rounded-2xl p-4 focus:ring-4 focus:ring-[#03045E]/20 focus:border-[#03045E] transition-all font-medium">
                        <option value="">-- Pilih Kelas Terlebih Dahulu --</option>
                        @foreach($kelasList as $kelas)
                            <option value="{{ $kelas->id }}">{{ $kelas->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-[#03045E] mb-2 uppercase tracking-widest">Pilih Siswa</label>
                    <select id="siswa_nisn" name="siswa_nisn" required
                        class="w-full bg-gray-50 border border-gray-200 text-[#03045E] rounded-2xl p-4 focus:ring-4 focus:ring-[#03045E]/20 focus:border-[#03045E] transition-all font-medium" disabled>
                        <option value="">-- Pilih Siswa --</option>
                    </select>
                    @error('siswa_nisn') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-[#03045E] mb-2 uppercase tracking-widest">Jenis Layanan</label>
                        <select name="jenis_layanan" required class="w-full bg-gray-50 border border-gray-200 text-[#03045E] rounded-2xl p-4 focus:ring-4 focus:ring-[#03045E]/20 focus:border-[#03045E] transition-all font-medium">
                            <option value="Konseling Pribadi" {{ old('jenis_layanan') == 'Konseling Pribadi' ? 'selected' : '' }}>Konseling Pribadi</option>
                            <option value="Konseling Kelompok" {{ old('jenis_layanan') == 'Konseling Kelompok' ? 'selected' : '' }}>Konseling Kelompok</option>
                            <option value="Bimbingan Karir" {{ old('jenis_layanan') == 'Bimbingan Karir' ? 'selected' : '' }}>Bimbingan Karir</option>
                            <option value="Bimbingan Belajar" {{ old('jenis_layanan') == 'Bimbingan Belajar' ? 'selected' : '' }}>Bimbingan Belajar</option>
                        </select>
                        @error('jenis_layanan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-[#03045E] mb-2 uppercase tracking-widest">Topik / Bahasan</label>
                        <input type="text" name="topik" required value="{{ old('topik') }}"
                            class="w-full bg-gray-50 border border-gray-200 text-[#03045E] rounded-2xl p-4 focus:ring-4 focus:ring-[#03045E]/20 focus:border-[#03045E] transition-all font-medium"
                            placeholder="Misal: Kesulitan Belajar Matematika">
                        @error('topik') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-[#03045E] mb-2 uppercase tracking-widest">Deskripsi Kasus (Opsional)</label>
                    <textarea name="deskripsi_kasus" rows="3"
                        class="w-full bg-gray-50 border border-gray-200 text-[#03045E] rounded-2xl p-4 focus:ring-4 focus:ring-[#03045E]/20 focus:border-[#03045E] transition-all font-medium"
                        placeholder="Detail masalah...">{{ old('deskripsi_kasus') }}</textarea>
                    @error('deskripsi_kasus') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-[#03045E] mb-2 uppercase tracking-widest">Tindak Lanjut (Opsional)</label>
                    <textarea name="tindak_lanjut" rows="3"
                        class="w-full bg-gray-50 border border-gray-200 text-[#03045E] rounded-2xl p-4 focus:ring-4 focus:ring-[#03045E]/20 focus:border-[#03045E] transition-all font-medium"
                        placeholder="Saran/Hasil...">{{ old('tindak_lanjut') }}</textarea>
                    @error('tindak_lanjut') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <button type="submit" class="w-full py-4 bg-[#03045E] text-white rounded-2xl font-bold shadow-lg hover:bg-blue-900 transition-all uppercase tracking-widest">
                    Simpan Data
                </button>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('kelas_id').addEventListener('change', function() {
            let kelasId = this.value;
            let siswaDropdown = document.getElementById('siswa_nisn');

            siswaDropdown.innerHTML = '<option value="">Memuat data...</option>';
            siswaDropdown.disabled = true;

            if (kelasId) {
                fetch(`/api/kelas/${kelasId}/siswa`)
                    .then(response => response.json())
                    .then(data => {
                        siswaDropdown.innerHTML = '<option value="">-- Pilih Siswa --</option>';
                        data.forEach(siswa => {
                            siswaDropdown.innerHTML += `<option value="${siswa.nisn}">${siswa.nama_lengkap} (${siswa.nisn})</option>`;
                        });
                        siswaDropdown.disabled = false;
                    })
                    .catch(error => {
                        console.error('Error fetching data:', error);
                        siswaDropdown.innerHTML = '<option value="">Gagal memuat data</option>';
                    });
            } else {
                siswaDropdown.innerHTML = '<option value="">-- Pilih Siswa --</option>';
            }
        });
    </script>
</x-app-layout>