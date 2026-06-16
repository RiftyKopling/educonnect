<x-app-layout>
    <div class="max-w-4xl mx-auto">
        <!-- Breadcrumb -->
        <div class="mb-8">
            <div class="flex items-center gap-2 text-sm text-gray-500 mb-2">
                <a href="{{ route('dashboard') }}" class="hover:text-[#03045E] font-medium">
                    Dashboard
                </a>
                <span>›</span>
                <a href="{{ route('siswa.index') }}" class="hover:text-[#03045E] font-medium">Manajemen Siswa</a>
                <span>›</span>
                <span class="text-[#03045E] font-bold">Edit Siswa</span>
            </div>
            <a href="{{ route('siswa.index') }}" class="flex items-center gap-2 text-sm text-gray-500 hover:text-[#03045E] font-medium mb-4">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali
            </a>
            <h2 class="text-3xl font-black text-[#03045E] tracking-tight">Edit Siswa: {{ $siswa->nama_lengkap }}</h2>
            <p class="text-gray-500 mt-1">Memperbarui informasi akademik siswa yang terdaftar di SMP Negeri 2 Mungkid.</p>
        </div>

        <!-- Session Error -->
        @if(session('error'))
            <div id="notif-error" class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-2xl flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M12 3a9 9 0 100 18A9 9 0 0012 3z"/>
                    </svg>
                    <span class="font-medium">{{ session('error') }}</span>
                </div>
                <button onclick="tutupNotifError()" class="text-red-700 hover:text-red-900 ml-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        @endif

        <!-- Error Handling -->
        @if($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-400 text-red-700 rounded-2xl">
                <div class="flex items-center gap-2 mb-2">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M12 3a9 9 0 100 18A9 9 0 0012 3z"/>
                    </svg>
                    <span class="font-bold">Proses Update Siswa Dibatalkan, terdapat {{ $errors->count() }} kesalahan:</span>
                </div>
                <ul class="list-disc list-inside space-y-1 text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-[2rem] shadow-sm p-8 border border-gray-100">
            <form action="{{ route('siswa.update', $siswa->nisn) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="space-y-6">
                    <!-- NISN & Nama Lengkap -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-[#03045E] mb-2 uppercase tracking-widest">NISN <span class="text-red-500">*</span></label>
                            <input type="text" 
                                name="nisn" 
                                value="{{ old('nisn', $siswa->nisn) }}" 
                                class="w-full rounded-2xl border-gray-200 focus:ring-[#03045E] focus:border-[#03045E] p-4" 
                                placeholder="10 Digit Angka"
                                required>
                            @error('nisn') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-[#03045E] mb-2 uppercase tracking-widest">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" 
                                name="nama_lengkap" 
                                value="{{ old('nama_lengkap', $siswa->nama_lengkap) }}" 
                                class="w-full rounded-2xl border-gray-200 focus:ring-[#03045E] focus:border-[#03045E] p-4" 
                                placeholder="Nama Lengkap Siswa"
                                required>
                            @error('nama_lengkap') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Jenis Kelamin & Tempat Lahir -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-[#03045E] mb-2 uppercase tracking-widest">Jenis Kelamin <span class="text-red-500">*</span></label>
                            <select name="jenis_kelamin" class="w-full rounded-2xl border-gray-200 focus:ring-[#03045E] focus:border-[#03045E] p-4" required>
                                <option value="L" {{ old('jenis_kelamin', $siswa->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('jenis_kelamin', $siswa->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('jenis_kelamin') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-[#03045E] mb-2 uppercase tracking-widest">Tempat Lahir <span class="text-red-500">*</span></label>
                            <input type="text" 
                                name="tempat_lahir" 
                                value="{{ old('tempat_lahir', $siswa->tempat_lahir) }}" 
                                class="w-full rounded-2xl border-gray-200 focus:ring-[#03045E] focus:border-[#03045E] p-4" 
                                placeholder="Masukkan tempat lahir"
                                required>
                            @error('tempat_lahir') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Tanggal Lahir & Kelas -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-[#03045E] mb-2 uppercase tracking-widest">Tanggal Lahir <span class="text-red-500">*</span></label>
                            <input type="date" 
                                name="tanggal_lahir" 
                                value="{{ old('tanggal_lahir', $siswa->tanggal_lahir) }}" 
                                class="w-full rounded-2xl border-gray-200 focus:ring-[#03045E] focus:border-[#03045E] p-4"
                                required>
                            @error('tanggal_lahir') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-[#03045E] mb-2 uppercase tracking-widest">Penempatan Kelas <span class="text-red-500">*</span></label>
                            <select name="kelas_id" class="w-full rounded-2xl border-gray-200 focus:ring-[#03045E] focus:border-[#03045E] p-4" required>
                                <option value="">Pilih Kelas...</option>
                                @foreach($data_kelas as $kelas)
                                    <option value="{{ $kelas->id }}" {{ old('kelas_id', $siswa->kelas_id) == $kelas->id ? 'selected' : '' }}>
                                        {{ strtoupper(str_replace('-', ' ', $kelas->nama_kelas)) }} (Tingkat {{ $kelas->tingkat }})
                                    </option>
                                @endforeach
                            </select>
                            @error('kelas_id') <p class="text-red-500 text-xs mt-1">{{ str_replace('kelas id', 'kelas', $message) }}</p> @enderror
                        </div>
                    </div>

                    <!-- Orang Tua / Wali -->
                    <div>
                        <label class="block text-sm font-bold text-[#03045E] mb-2 uppercase tracking-widest">Pilih Orang Tua / Wali</label>
                        <select name="orang_tua_id" class="w-full rounded-2xl border-gray-200 focus:ring-[#03045E] focus:border-[#03045E] p-4">
                            <option value="">-- Pilih Akun Orang Tua (Opsional) --</option>
                            @foreach($data_ortu as $ortu)
                                <option value="{{ $ortu->id }}" {{ old('orang_tua_id', $siswa->orang_tua_id) == $ortu->id ? 'selected' : '' }}>
                                    {{ $ortu->name }} ({{ $ortu->email }})
                                </option>
                            @endforeach
                        </select>
                        <p class="text-[10px] text-gray-400 mt-1 font-medium">*Pastikan akun Orang Tua sudah didaftarkan di menu Manajemen Pengguna terlebih dahulu.</p>
                        @error('orang_tua_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Alamat Lengkap -->
                    <div>
                        <label class="block text-sm font-bold text-[#03045E] mb-2 uppercase tracking-widest">Alamat Lengkap</label>
                        <textarea name="alamat" 
                            rows="3" 
                            class="w-full rounded-2xl border-gray-200 focus:ring-[#03045E] focus:border-[#03045E] p-4 resize-none">{{ old('alamat', $siswa->alamat) }}</textarea>
                        @error('alamat') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-bold text-[#03045E] mb-2 uppercase tracking-widest">Status</label>
                        <select name="status" class="w-full rounded-2xl border-gray-200 focus:ring-[#03045E] focus:border-[#03045E] p-4">
                            <option value="aktif" {{ old('status', $siswa->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="lulus" {{ old('status', $siswa->status) == 'lulus' ? 'selected' : '' }}>Lulus</option>
                            <option value="pindah" {{ old('status', $siswa->status) == 'pindah' ? 'selected' : '' }}>Pindah</option>
                            <option value="keluar" {{ old('status', $siswa->status) == 'keluar' ? 'selected' : '' }}>Keluar</option>
                        </select>
                        @error('status') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full py-4 bg-amber-500 text-white rounded-2xl font-bold shadow-lg hover:bg-amber-600 transition-all uppercase tracking-widest">
                        Perbarui Data Siswa
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function tutupNotifError() {
            const notif = document.getElementById('notif-error');
            if (notif) {
                notif.style.transition = 'opacity 0.5s';
                notif.style.opacity = '0';
                setTimeout(() => notif.remove(), 500);
            }
        }

        // Auto hilang setelah 5 detik
        setTimeout(tutupNotifError, 5000);
    </script>
</x-app-layout>