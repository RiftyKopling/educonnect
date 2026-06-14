<x-app-layout>
    <div class="max-w-3xl mx-auto">
        <div class="mb-8">
            <a href="{{ route('siswa.index') }}" class="text-[#03045E] font-bold flex items-center gap-2 mb-2 hover:opacity-80 transition-opacity">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Daftar Siswa
            </a>
            <h2 class="text-3xl font-black text-[#03045E] tracking-tight">Ubah Data Siswa</h2>
            <p class="text-gray-500 mt-1">Memperbarui informasi akademik untuk {{ $siswa->nama_lengkap }}</p>
        </div>

        <div class="bg-white rounded-[2rem] shadow-sm p-8 border border-gray-100">
            <form action="{{ route('siswa.update', $siswa->nisn) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="space-y-6">
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-[#03045E] mb-2 uppercase tracking-widest">NISN</label>
                            <input type="text" name="nisn" value="{{ old('nisn', $siswa->nisn) }}" class="w-full rounded-2xl border-gray-200 focus:ring-[#03045E] focus:border-[#03045E] p-4" placeholder="10 Digit Angka">
                            @error('nisn') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-[#03045E] mb-2 uppercase tracking-widest">Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $siswa->nama_lengkap) }}" class="w-full rounded-2xl border-gray-200 focus:ring-[#03045E] focus:border-[#03045E] p-4" placeholder="Nama Lengkap Siswa">
                            @error('nama_lengkap') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-[#03045E] mb-2 uppercase tracking-widest">Jenis Kelamin</label>
                            <select name="jenis_kelamin" class="w-full rounded-2xl border-gray-200 focus:ring-[#03045E] focus:border-[#03045E] p-4">
                                <option value="L" {{ old('jenis_kelamin', $siswa->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('jenis_kelamin', $siswa->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('jenis_kelamin') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-[#03045E] mb-2 uppercase tracking-widest">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $siswa->tanggal_lahir) }}" class="w-full rounded-2xl border-gray-200 focus:ring-[#03045E] focus:border-[#03045E] p-4">
                            @error('tanggal_lahir') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-[#03045E] mb-2 uppercase tracking-widest">Penempatan Kelas</label>
                        <select name="kelas_id" class="w-full rounded-2xl border-gray-200 focus:ring-[#03045E] focus:border-[#03045E] p-4">
                            <option value="">Pilih Kelas...</option>
                            @foreach($data_kelas as $kelas)
                                <option value="{{ $kelas->id }}" {{ old('kelas_id', $siswa->kelas_id) == $kelas->id ? 'selected' : '' }}>
                                    Kelas {{ $kelas->nama_kelas }} (Tingkat {{ $kelas->tingkat }})
                                </option>
                            @endforeach
                        </select>
                        @error('kelas_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Orang Tua -->
                    <div>
                        <label class="block text-xs font-black text-[#03045E] mb-2 uppercase tracking-widest">Pilih Orang Tua / Wali</label>
                        <select name="orang_tua_id" class="w-full rounded-2xl border-gray-100 bg-gray-50 p-4 font-bold focus:ring-2 focus:ring-[#03045E] focus:bg-white transition-all text-[#03045E]">
                            <option value="">-- Pilih Akun Orang Tua (Opsional) --</option>
                            @foreach($data_ortu as $ortu)
                                <option value="{{ $ortu->id }}" {{ old('orang_tua_id') == $ortu->id ? 'selected' : '' }}>
                                    {{ $ortu->name }} ({{ $ortu->email }})
                                </option>
                            @endforeach
                        </select>
                        <p class="text-[10px] text-gray-400 mt-1 font-medium">*Pastikan akun Orang Tua sudah didaftarkan di menu Manajemen Pengguna terlebih dahulu.</p>
                        @error('orang_tua_id') <p class="text-red-500 text-xs mt-2 font-bold">{{ $message }}</p> @enderror
                    </div>

                    <button type="submit" class="w-full py-4 mt-4 bg-[#03045E] text-white rounded-2xl font-bold shadow-lg hover:bg-blue-900 transition-all uppercase tracking-widest">
                        Simpan Perubahan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>