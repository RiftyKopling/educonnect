<x-app-layout>
    <div class="max-w-3xl mx-auto">
        <div class="mb-8">
            <div class="flex items-center gap-2 text-sm text-gray-500 mb-2">
                <a href="{{ route('dashboard') }}" class="hover:text-[#03045E] font-medium">
                    Dashboard
                </a>
                <span>›</span>
                <a href="{{ route('pengumuman.index') }}" class="hover:text-[#03045E] font-medium">Manajemen Pengumuman</a>
                <span>›</span>
                <span class="text-[#03045E] font-bold">Tambah Pengumuman Baru</span>
            </div>
            <a href="{{ route('pengumuman.index') }}" class="flex items-center gap-2 text-sm text-gray-500 hover:text-[#03045E] font-medium mb-4">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali
            </a>
            <h2 class="text-3xl font-black text-[#03045E] tracking-tight">Buat Pengumuman Baru</h2>
        </div>

        <div class="bg-white rounded-[2rem] shadow-sm p-8 border border-gray-100">
            <form action="{{ route('pengumuman.store') }}" method="POST" enctype="multipart/form-data">
                @if($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border border-red-400 text-red-700 rounded-2xl">
                        <div class="flex items-center gap-2 mb-2">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M12 3a9 9 0 100 18A9 9 0 0012 3z"/>
                            </svg>
                            <span class="font-bold">Proses Tambah Pengumuman Dibatalkan, terdapat {{ $errors->count() }} kesalahan:</span>
                        </div>
                        <ul class="list-disc list-inside space-y-1 text-sm">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                @csrf
                <div class="space-y-6">
                    <!-- Judul -->
                    <div>
                        <label class="block text-sm font-bold text-[#03045E] mb-2 uppercase tracking-widest">Judul Pengumuman</label>
                        <input type="text" 
                               name="judul" 
                               value="{{ old('judul') }}" 
                               class="w-full rounded-2xl border-gray-200 focus:ring-[#03045E] focus:border-[#03045E] p-4" 
                               placeholder="Contoh: Jadwal Libur Semester Genap">
                        @error('judul') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Kirim Kepada (Target) -->
                    <div>
                        <label class="block text-sm font-bold text-[#03045E] mb-2 uppercase tracking-widest">Kirim Kepada</label>
                        <select name="target" class="w-full rounded-2xl border-gray-200 focus:ring-[#03045E] focus:border-[#03045E] p-4">
                            @php $role = auth()->user()->role?->slug; @endphp
                            
                            @if($role == 'admin-sekolah')
                                <option value="all" {{ old('target') == 'all' ? 'selected' : '' }}>Semua User</option>
                            @elseif($role == 'kepala-sekolah')
                                <option value="all" {{ old('target') == 'all' ? 'selected' : '' }}>Seluruh Warga Sekolah (Global)</option>
                                {{-- Nanti tambahkan opsi lain untuk Kepsek --}}
                            @elseif($role == 'wali-kelas')
                                <option value="class-parents" {{ old('target') == 'class-parents' ? 'selected' : '' }}>Orang Tua Murid (Kelas {{ $kelas_diampu?->nama_kelas ?? 'Ampuan Anda' }})</option>
                                <option value="kepala-sekolah" {{ old('target') == 'kepala-sekolah' ? 'selected' : '' }}>Kepala Sekolah (Internal)</option>
                            @elseif($role == 'guru-mapel' || $role == 'guru-bk')
                                <option value="all-parents" {{ old('target') == 'all-parents' ? 'selected' : '' }}>Seluruh Orang Tua Murid</option>
                                <option value="kepala-sekolah" {{ old('target') == 'kepala-sekolah' ? 'selected' : '' }}>Kepala Sekolah (Internal)</option>
                            @else
                                <option value="all" {{ old('target') == 'all' ? 'selected' : '' }}>Semua User</option>
                            @endif
                        </select>
                        @error('target') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Isi Pengumuman -->
                    <div>
                        <label class="block text-sm font-bold text-[#03045E] mb-2 uppercase tracking-widest">Isi Pengumuman</label>
                        <textarea name="konten" 
                                  rows="6" 
                                  class="w-full rounded-2xl border-gray-200 focus:ring-[#03045E] focus:border-[#03045E] p-4" 
                                  placeholder="Tulis rincian pengumuman secara mendetail di sini..." >{{ old('konten') }}</textarea>
                        @error('konten') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- File Lampiran -->
                    <div>
                        <label class="block text-sm font-bold text-[#03045E] mb-2 uppercase tracking-widest">Lampiran (Opsional)</label>
                        <div class="relative">
                            <input type="file" 
                                name="file_lampiran" 
                                accept=".jpg,.jpeg,.png,.pdf"
                                onchange="tampilkanUkuranFile(this)"
                                class="w-full rounded-2xl border-gray-200 focus:ring-[#03045E] focus:border-[#03045E] p-4 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-[#03045E] file:text-white hover:file:bg-blue-900 transition-all">
                            <p class="text-xs text-gray-400 mt-1">*Format yang didukung: JPG, PNG, PDF (Maks. 4MB)</p>
                        </div>
                        @error('file_lampiran') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Tombol Submit -->
                    <button type="submit" 
                            class="w-full py-4 bg-[#03045E] text-white rounded-2xl font-bold shadow-lg hover:bg-blue-900 transition-all uppercase tracking-widest">
                        Kirim Pengumuman
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function tampilkanUkuranFile(input) {
            const info = document.getElementById('info-file');
            const file = input.files[0];
            
            if (file) {
                const sizeInKB = (file.size / 1024).toFixed(2);
                const sizeInMB = (file.size / (1024 * 1024)).toFixed(2);
                const maxSizeMB = 4; // Ubah dari 2 ke 4
                
                let ukuranText = `Ukuran: ${sizeInKB} KB (${sizeInMB} MB)`;
                let warna = 'text-gray-500';
                
                if (file.size > maxSizeMB * 1024 * 1024) {
                    ukuranText += ' ⚠️ Melebihi batas 4MB!';
                    warna = 'text-red-500 font-bold';
                }
                
                const allowedTypes = ['image/jpeg', 'image/png', 'application/pdf'];
                if (!allowedTypes.includes(file.type)) {
                    ukuranText += ' ⚠️ Format tidak didukung!';
                    warna = 'text-red-500 font-bold';
                }
                
                info.innerHTML = `<span class="${warna}">${ukuranText}</span>`;
            } else {
                info.innerHTML = '';
            }
        }
    </script>
</x-app-layout>