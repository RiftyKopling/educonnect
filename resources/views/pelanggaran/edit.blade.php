<x-app-layout>
    <div class="max-w-3xl mx-auto">
        <div class="mb-8">
            <!-- Breadcrumb -->
            <div class="flex items-center gap-2 text-sm text-gray-500 mb-2">
                <a href="{{ route('dashboard') }}" class="hover:text-[#03045E] font-medium">Dashboard</a>
                <span>›</span>
                <a href="{{ route('pelanggaran.index') }}" class="hover:text-[#03045E] font-medium">Master Pelanggaran</a>
                <span>›</span>
                <span class="text-[#03045E] font-bold">Edit Pelanggaran</span>
            </div>

            <!-- Tombol Kembali -->
            <a href="{{ route('pelanggaran.index') }}" class="flex items-center gap-2 text-sm text-gray-500 hover:text-[#03045E] font-medium mb-4">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali
            </a>

            <!-- Header -->
            <h2 class="text-3xl font-black text-[#03045E] tracking-tight">Edit Pelanggaran</h2>
            <p class="text-gray-500 text-sm mt-1">Edit data pelanggaran yang sudah ada.</p>
        </div>

        <!-- Error Handling -->
        @if($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-400 text-red-700 rounded-2xl">
                <div class="flex items-center gap-2 mb-2">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M12 3a9 9 0 100 18A9 9 0 0012 3z"/>
                    </svg>
                    <span class="font-bold">Proses Edit Pelanggaran Dibatalkan, terdapat {{ $errors->count() }} kesalahan:</span>
                </div>
                <ul class="list-disc list-inside space-y-1 text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-400 text-red-700 rounded-2xl">
                <span class="font-bold">{{ session('error') }}</span>
            </div>
        @endif

        <div class="bg-white rounded-[2rem] shadow-sm p-8 border border-gray-100">
            <form action="{{ route('pelanggaran.update', $pelanggaran->id) }}" method="POST">
                @csrf @method('PUT')
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-bold text-[#03045E] mb-2 uppercase tracking-widest">Nama Pelanggaran</label>
                        <input type="text" 
                            name="nama_pelanggaran" 
                            value="{{ old('nama_pelanggaran', $pelanggaran->nama_pelanggaran) }}" 
                            class="w-full rounded-2xl border-gray-200 focus:ring-[#03045E] focus:border-[#03045E] p-4" 
                            placeholder="Contoh: Terlambat Masuk Sekolah">
                        @error('nama_pelanggaran') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-[#03045E] mb-2 uppercase tracking-widest">Kategori</label>
                        <select name="kategori"  
                            class="w-full rounded-2xl border-gray-200 focus:ring-[#03045E] focus:border-[#03045E] p-4">
                            <option value="Ringan" {{ old('kategori', $pelanggaran->kategori) == 'Ringan' ? 'selected' : '' }}>Ringan</option>
                            <option value="Sedang" {{ old('kategori', $pelanggaran->kategori) == 'Sedang' ? 'selected' : '' }}>Sedang</option>
                            <option value="Berat" {{ old('kategori', $pelanggaran->kategori) == 'Berat' ? 'selected' : '' }}>Berat</option>
                        </select>
                        @error('kategori') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-[#03045E] mb-2 uppercase tracking-widest">Deskripsi (opsional)</label>
                        <textarea name="deskripsi" 
                            rows="3" 
                            class="w-full rounded-2xl border-gray-200 focus:ring-[#03045E] focus:border-[#03045E] p-4">{{ old('deskripsi', $pelanggaran->deskripsi) }}</textarea>
                        @error('deskripsi') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <button type="submit" class="w-full py-4 bg-[#03045E] text-white rounded-2xl font-bold shadow-lg hover:bg-blue-900 transition-all uppercase tracking-widest">
                        Perbarui Data Pelanggaran
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>