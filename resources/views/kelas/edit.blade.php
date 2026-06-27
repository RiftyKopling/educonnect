<x-app-layout>
    <div class="max-w-4xl mx-auto">
        <!-- Breadcrumb -->
        <div class="mb-8">
            <div class="flex items-center gap-2 text-sm text-gray-500 mb-2">
                <a href="{{ route('dashboard') }}" class="hover:text-[#03045E] font-medium">
                    Dashboard
                </a>
                <span>›</span>
                <a href="{{ route('kelas.index') }}" class="hover:text-[#03045E] font-medium">Manajemen Kelas</a>
                <span>›</span>
                <span class="text-[#03045E] font-bold">Edit Kelas</span>
            </div>
            <a href="{{ route('kelas.index') }}" class="flex items-center gap-2 text-sm text-gray-500 hover:text-[#03045E] font-medium mb-4">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali
            </a>
            <h2 class="text-3xl font-black text-[#03045E] tracking-tight">Edit Kelas: {{ $kelas->nama_kelas }}</h2>
            <p class="text-gray-500 text-sm mt-1">Memperbarui informasi kelas yang terdaftar di sistem akademik.</p>
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
                    <span class="font-bold">Proses Update Kelas Dibatalkan, terdapat {{ $errors->count() }} kesalahan:</span>
                </div>
                <ul class="list-disc list-inside space-y-1 text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-[2rem] shadow-sm p-8 border border-gray-100">
            <form action="{{ route('kelas.update', $kelas->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="space-y-6">
                    <!-- Nama Kelas & Tingkat -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-[#03045E] mb-2 uppercase tracking-widest">Nama Kelas</label>
                            <input type="text" 
                                name="nama_kelas" 
                                value="{{ old('nama_kelas', $kelas->nama_kelas) }}" 
                                class="w-full rounded-2xl border-gray-200 focus:ring-[#03045E] focus:border-[#03045E] p-4" 
                                placeholder="Contoh: 7A, 8A, 9A">
                            @error('nama_kelas') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-[#03045E] mb-2 uppercase tracking-widest">Tingkat</label>
                            <select name="tingkat" class="w-full rounded-2xl border-gray-200 focus:ring-[#03045E] focus:border-[#03045E] p-4">
                                <option value="">Pilih Tingkat...</option>
                                @foreach([7,8,9] as $t)
                                    <option value="{{ $t }}" {{ old('tingkat', $kelas->tingkat) == $t ? 'selected' : '' }}>Tingkat {{ $t }}</option>
                                @endforeach
                            </select>
                            @error('tingkat') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Tahun Ajaran & Wali Kelas -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-[#03045E] mb-2 uppercase tracking-widest">Tahun Ajaran</label>
                            <input type="text" 
                                name="tahun_ajaran" 
                                value="{{ old('tahun_ajaran', $kelas->tahun_ajaran) }}" 
                                class="w-full rounded-2xl border-gray-200 focus:ring-[#03045E] focus:border-[#03045E] p-4" 
                                placeholder="Contoh: 2025/2026">
                            @error('tahun_ajaran') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-[#03045E] mb-2 uppercase tracking-widest">Ganti Wali Kelas</label>
                            <select name="wali_kelas_id" class="w-full rounded-2xl border-gray-200 focus:ring-[#03045E] focus:border-[#03045E] p-4">
                                <option value="">-- Belum ada Wali Kelas --</option>
                                @foreach($data_guru as $guru)
                                    <option value="{{ $guru->id }}" {{ old('wali_kelas_id', $kelas->wali_kelas_id) == $guru->id ? 'selected' : '' }}>
                                        {{ $guru->name }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-[12px] text-gray-400 mt-1 font-medium">*Pastikan akun Guru dengan role Wali Kelas sudah didaftarkan terlebih dahulu.</p>
                            @error('wali_kelas_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full py-4 bg-[#03045E] text-white rounded-2xl font-bold shadow-lg hover:bg-amber-600 transition-all uppercase tracking-widest">
                        Perbarui Data Kelas
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