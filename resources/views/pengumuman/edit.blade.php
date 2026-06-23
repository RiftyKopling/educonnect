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
                <span class="text-[#03045E] font-bold">Edit Pengumuman</span>
            </div>
            <a href="{{ route('pengumuman.index') }}" class="flex items-center gap-2 text-sm text-gray-500 hover:text-[#03045E] font-medium mb-4">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali
            </a>
            <h2 class="text-3xl font-black text-[#03045E] tracking-tight">Edit Pengumuman</h2>
            <p class="text-gray-500 text-sm mt-1">Perbarui informasi pengumuman yang telah dibuat</p>
        </div>

        <div class="bg-white rounded-[2rem] shadow-sm p-8 border border-gray-100">
            <form action="{{ route('pengumuman.update', $pengumuman->id) }}" method="POST" enctype="multipart/form-data">
                @method('PUT')
                
                @if($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border border-red-400 text-red-700 rounded-2xl">
                        <div class="flex items-center gap-2 mb-2">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M12 3a9 9 0 100 18A9 9 0 0012 3z"/>
                            </svg>
                            <span class="font-bold">Proses Update Pengumuman Dibatalkan, terdapat {{ $errors->count() }} kesalahan:</span>
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
                    <!-- Informasi Pembuat (Readonly) -->
                    <div class="bg-gray-50 rounded-2xl p-4 flex items-center gap-3 border border-gray-200">
                        <div class="w-10 h-10 rounded-full bg-[#03045E] flex items-center justify-center text-white text-sm font-bold">
                            {{ substr($pengumuman->user?->name, 0, 1) }}
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Dibuat oleh</p>
                            <p class="text-sm font-bold text-[#03045E]">{{ $pengumuman->user?->name }}</p>
                        </div>
                        <div class="ml-auto text-right">
                            <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Tanggal Dibuat</p>
                            <p class="text-sm font-bold text-[#03045E]">{{ $pengumuman->created_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>

                    <!-- Judul -->
                    <div>
                        <label class="block text-sm font-bold text-[#03045E] mb-2 uppercase tracking-widest">Judul Pengumuman</label>
                        <input type="text" 
                               name="judul" 
                               value="{{ old('judul', $pengumuman->judul) }}" 
                               class="w-full rounded-2xl border-gray-200 focus:ring-[#03045E] focus:border-[#03045E] p-4" 
                               placeholder="Contoh: Jadwal Libur Semester Genap" 
                               required>
                        @error('judul') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Kirim Kepada (Target) -->
                    <div>
                        <label class="block text-sm font-bold text-[#03045E] mb-2 uppercase tracking-widest">Kirim Kepada</label>
                        <select name="target" class="w-full rounded-2xl border-gray-200 focus:ring-[#03045E] focus:border-[#03045E] p-4">
                            @php $role = auth()->user()->role?->slug; @endphp
                            
                            @if($role == 'admin-sekolah')
                                <option value="all" {{ old('target', $pengumuman->target_type) == 'all' ? 'selected' : '' }}>Semua User</option>
                            @elseif($role == 'kepala-sekolah')
                                <option value="all" {{ old('target', $pengumuman->target_type) == 'all' ? 'selected' : '' }}>Seluruh Warga Sekolah (Global)</option>
                                {{-- Nanti tambahkan opsi lain untuk Kepsek --}}
                            @elseif($role == 'wali-kelas')
                                <option value="class-parents" {{ old('target', $pengumuman->target_type) == 'class-parents' ? 'selected' : '' }}>Orang Tua Murid (Kelas {{ $kelas_diampu?->nama_kelas ?? 'Ampuan Anda' }})</option>
                                <option value="kepala-sekolah" {{ old('target', $pengumuman->target_type) == 'kepala-sekolah' ? 'selected' : '' }}>Kepala Sekolah (Internal)</option>
                            @elseif($role == 'guru-mapel' || $role == 'guru-bk')
                                <option value="all-parents" {{ old('target', $pengumuman->target_type) == 'all-parents' ? 'selected' : '' }}>Seluruh Orang Tua Murid</option>
                                <option value="kepala-sekolah" {{ old('target', $pengumuman->target_type) == 'kepala-sekolah' ? 'selected' : '' }}>Kepala Sekolah (Internal)</option>
                            @else
                                <option value="all" {{ old('target', $pengumuman->target_type) == 'all' ? 'selected' : '' }}>Semua User</option>
                            @endif
                        </select>
                        @error('target') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        
                        <!-- Informasi Target Saat Ini -->
                        <div class="mt-2 flex items-center gap-2">
                            <span class="text-xs text-gray-400 font-bold">Target saat ini:</span>
                            <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-bold uppercase">
                                {{ str_replace('-', ' ', $pengumuman->target_type) }}
                            </span>
                        </div>
                    </div>

                    <!-- Isi Pengumuman -->
                    <div>
                        <label class="block text-sm font-bold text-[#03045E] mb-2 uppercase tracking-widest">Isi Pengumuman</label>
                        <textarea name="konten" 
                                  rows="6" 
                                  class="w-full rounded-2xl border-gray-200 focus:ring-[#03045E] focus:border-[#03045E] p-4" 
                                  placeholder="Tulis rincian pengumuman secara mendetail di sini..." 
                                  required>{{ old('konten', $pengumuman->konten) }}</textarea>
                        @error('konten') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- File Lampiran -->
                    <div>
                        <label class="block text-sm font-bold text-[#03045E] mb-2 uppercase tracking-widest">Lampiran (Opsional)</label>
                        @if($pengumuman->file_lampiran)
                            <div id="lampiran-container" class="mb-3 p-4 bg-gray-50 rounded-2xl border border-gray-200">
                                <!-- Nama File + Ukuran -->
                                <div class="flex items-center gap-2 mb-3">
                                    <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V7l-5-5z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 2v5h5M9 13l3-3m0 0l3 3m-3-3v8"/>
                                    </svg>
                                    <span class="text-sm font-bold text-[#03045E]">{{ basename($pengumuman->file_lampiran) }}</span>
                                    <span class="text-xs text-gray-400">({{ number_format(Storage::disk('public')->size($pengumuman->file_lampiran) / 1024, 2) }} KB)</span>
                                </div>
                                
                                <!-- Tombol Aksi -->
                                <div class="flex items-center gap-3">
                                    <a href="{{ asset('storage/' . $pengumuman->file_lampiran) }}" 
                                    target="_blank" 
                                    class="px-4 py-2 bg-[#03045E] text-white rounded-xl text-xs font-bold hover:bg-blue-900 transition-all flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V7l-5-5z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 2v5h5M9 13l3-3m0 0l3 3m-3-3v8"/>
                                        </svg>
                                        Lihat File
                                    </a>
                                    <button type="button" 
                                            onclick="bukaModalHapusLampiran('{{ $pengumuman->id }}', '{{ basename($pengumuman->file_lampiran) }}')"
                                            class="px-4 py-2 bg-red-100 text-red-600 rounded-xl text-xs font-bold hover:bg-red-200 transition-all flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        Hapus
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Hidden input untuk hapus lampiran -->
                            <input type="hidden" id="hapus_lampiran" name="hapus_lampiran" value="0">
                        @endif

                        <!-- Input Upload File Baru -->
                        <input type="file" 
                            name="file_lampiran" 
                            accept=".jpg,.jpeg,.png,.pdf"
                            onchange="tampilkanUkuranFile(this)"
                            class="w-full rounded-2xl border-gray-200 focus:ring-[#03045E] focus:border-[#03045E] p-4 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-[#03045E] file:text-white hover:file:bg-blue-900 transition-all">
                        <div id="info-file" class="text-xs text-gray-500 mt-1"></div>
                        <p class="text-xs text-gray-400 mt-1">*Kosongkan jika tidak ingin mengganti lampiran. Format yang didukung: JPG, PNG, PDF (Maks. 4MB)</p>
                        @error('file_lampiran') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="pt-2 flex gap-3">
                        <button type="submit" 
                                class="flex-1 py-4 bg-[#03045E] text-white rounded-2xl font-bold shadow-lg hover:bg-blue-900 transition-all uppercase tracking-widest">
                            Update Pengumuman
                        </button>
                        <a href="{{ route('pengumuman.index') }}" 
                           class="py-4 px-8 bg-gray-200 text-gray-700 rounded-2xl font-bold hover:bg-gray-300 transition-all uppercase tracking-widest flex items-center justify-center">
                            Batal
                        </a>
                    </div>
                </div>
            </form>

            <!-- Modal Konfirmasi Hapus Lampiran -->
            <div id="modal-hapus-lampiran" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50">
                <div class="bg-white rounded-2xl shadow-xl p-8 max-w-sm w-full mx-4 transform transition-all duration-300 scale-100">
                    <div class="flex flex-col items-center text-center gap-4">
                        <!-- Icon Warning -->
                        <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center animate-pulse">
                            <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        
                        <div>
                            <h3 class="text-lg font-black text-[#03045E]">Hapus Lampiran?</h3>
                            <p class="text-gray-500 text-sm mt-1">
                                File lampiran "<span id="nama-file-lampiran" class="font-bold text-[#03045E]"></span>"
                                akan dihapus permanen dan tidak bisa dikembalikan.
                            </p>
                        </div>

                        <!-- Informasi Tambahan -->
                        <div class="w-full bg-gray-50 rounded-xl p-3 text-left text-xs text-gray-600">
                            <div class="flex items-start gap-2">
                                <svg class="w-4 h-4 text-[#03045E] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>Lampiran akan dihapus saat pengumuman diupdate.</span>
                            </div>
                        </div>

                        <div class="flex gap-3 w-full mt-2">
                            <button onclick="tutupModalHapusLampiran()" 
                                    class="flex-1 py-3 bg-[#03045E] text-white rounded-xl font-bold hover:bg-[#05086b] transition-all">
                                Batal
                            </button>
                            <button onclick="konfirmasiHapusLampiran()" 
                                    class="flex-1 py-3 bg-red-600 text-white rounded-xl font-bold hover:bg-red-700 transition-all">
                                Hapus
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function tampilkanUkuranFile(input) {
            const info = document.getElementById('info-file');
            const file = input.files[0];
            
            if (file) {
                const sizeInKB = (file.size / 1024).toFixed(2);
                const sizeInMB = (file.size / (1024 * 1024)).toFixed(2);
                const maxSizeMB = 4;
                
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

        // ===== MODAL HAPUS LAMPIRAN =====
        let fileToDelete = '';

        function bukaModalHapusLampiran(pengumumanId, fileName) {
            fileToDelete = fileName;
            document.getElementById('nama-file-lampiran').textContent = fileName;
            const modal = document.getElementById('modal-hapus-lampiran');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            const content = modal.querySelector('.bg-white');
            content.style.transform = 'scale(0.9)';
            content.style.opacity = '0';
            setTimeout(() => {
                content.style.transform = 'scale(1)';
                content.style.opacity = '1';
            }, 50);
        }

        function tutupModalHapusLampiran() {
            const modal = document.getElementById('modal-hapus-lampiran');
            const content = modal.querySelector('.bg-white');
            content.style.transform = 'scale(0.9)';
            content.style.opacity = '0';
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                content.style.transform = 'scale(1)';
                content.style.opacity = '1';
            }, 300);
        }

        function konfirmasiHapusLampiran() {
            document.getElementById('hapus_lampiran').value = '1';
            
            const container = document.getElementById('lampiran-container');
            if (container) {
                container.style.transition = 'all 0.5s ease';
                container.style.opacity = '0';
                container.style.transform = 'translateX(20px)';
                setTimeout(() => {
                    container.style.display = 'none';
                }, 500);
                
                const parent = container.parentElement;
                if (parent) {
                    const successMsg = document.createElement('div');
                    successMsg.className = 'mb-3 p-3 bg-green-50 border border-green-200 rounded-2xl text-green-700 text-sm flex items-center gap-2';
                    successMsg.innerHTML = `
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>Lampiran berhasil dihapus dari daftar.</span>
                    `;
                    parent.insertBefore(successMsg, parent.querySelector('input[type="file"]'));
                }
            }
            
            tutupModalHapusLampiran();
        }

        // Tutup modal kalau klik di luar
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('modal-hapus-lampiran');
            if (modal) {
                modal.addEventListener('click', function(e) {
                    if (e.target === this) {
                        tutupModalHapusLampiran();
                    }
                });
            }
        });
    </script>
</x-app-layout>