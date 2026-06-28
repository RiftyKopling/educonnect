<x-app-layout>
    <div class="max-w-3xl mx-auto">
        <div class="mb-8">
            <div class="flex items-center gap-2 text-sm text-gray-500 mb-2">
                <a href="{{ route('dashboard') }}" class="hover:text-[#03045E] font-medium">Dashboard</a>
                <span>›</span>
                <a href="{{ route('materi-ajar.index') }}" class="hover:text-[#03045E] font-medium">Materi Ajar</a>
                <span>›</span>
                <span class="text-[#03045E] font-bold">Edit Materi</span>
            </div>
            <a href="{{ route('materi-ajar.index') }}" class="flex items-center gap-2 text-sm text-gray-500 hover:text-[#03045E] font-medium mb-4">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali
            </a>
            <h2 class="text-3xl font-black text-[#03045E] tracking-tight">Edit Materi: {{ $materiAjar->judul }}</h2>
            <p class="text-gray-500 text-sm mt-1">Perbarui dokumen atau tautan materi pembelajaran.</p>
        </div>

        <div class="bg-white rounded-[2rem] shadow-sm p-8 border border-gray-100">
            <form action="{{ route('materi-ajar.update', $materiAjar->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                @if(session('error'))
                    <div id="notif-error" class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-2xl flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M12 3a9 9 0 100 18A9 9 0 0012 3z"/></svg>
                            <span class="font-medium">{{ session('error') }}</span>
                        </div>
                        <button onclick="tutupNotifError()" class="text-red-700 hover:text-red-900 ml-4">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border border-red-400 text-red-700 rounded-2xl">
                        <div class="flex items-center gap-2 mb-2">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M12 3a9 9 0 100 18A9 9 0 0012 3z"/></svg>
                            <span class="font-bold">Ada {{ $errors->count() }} kesalahan:</span>
                        </div>
                        <ul class="list-disc list-inside space-y-1 text-sm">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="space-y-6">
                    <!-- Judul -->
                    <div>
                        <label class="block text-sm font-bold text-[#03045E] mb-2 uppercase tracking-widest">Judul Materi</label>
                        <input type="text" name="judul" value="{{ old('judul', $materiAjar->judul) }}" required
                            class="w-full bg-gray-50 border border-gray-200 text-[#03045E] rounded-2xl p-4 focus:ring-4 focus:ring-[#03045E]/20 focus:border-[#03045E] transition-all font-medium">
                        @error('judul') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Mapel & Kelas -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-[#03045E] mb-2 uppercase tracking-widest">Mata Pelajaran</label>
                            <select name="mapel_id" required class="w-full bg-gray-50 border border-gray-200 text-[#03045E] rounded-2xl p-4 focus:ring-4 focus:ring-[#03045E]/20 focus:border-[#03045E] transition-all font-medium">
                                <option value="">-- Pilih Mapel --</option>
                                @foreach($mapels as $mapel)
                                    <option value="{{ $mapel->id }}" {{ $materiAjar->mapel_id == $mapel->id ? 'selected' : '' }}>{{ $mapel->nama_mapel }}</option>
                                @endforeach
                            </select>
                            @error('mapel_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-[#03045E] mb-2 uppercase tracking-widest">Kelas Target</label>
                            <select name="kelas_id" required class="w-full bg-gray-50 border border-gray-200 text-[#03045E] rounded-2xl p-4 focus:ring-4 focus:ring-[#03045E]/20 focus:border-[#03045E] transition-all font-medium">
                                <option value="">-- Pilih Kelas --</option>
                                @foreach($kelas as $k)
                                    <option value="{{ $k->id }}" {{ $materiAjar->kelas_id == $k->id ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                                @endforeach
                            </select>
                            @error('kelas_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Deskripsi -->
                    <div>
                        <label class="block text-sm font-bold text-[#03045E] mb-2 uppercase tracking-widest">Deskripsi / Instruksi (Opsional)</label>
                        <textarea name="deskripsi" rows="3" class="w-full bg-gray-50 border border-gray-200 text-[#03045E] rounded-2xl p-4 focus:ring-4 focus:ring-[#03045E]/20 focus:border-[#03045E] transition-all font-medium">{{ old('deskripsi', $materiAjar->deskripsi) }}</textarea>
                        @error('deskripsi') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Tipe Materi -->
                    <div class="bg-gray-50 p-6 rounded-2xl border border-gray-200">
                        <label class="block text-sm font-bold text-[#03045E] mb-4 uppercase tracking-widest">Tipe Materi</label>

                        <div class="flex gap-6 mb-4">
                            <label class="flex items-center gap-2 cursor-pointer font-bold text-gray-700">
                                <input type="radio" name="tipe_materi" value="File" class="w-5 h-5 text-[#03045E] focus:ring-[#03045E]" onchange="toggleMateriInput()" {{ $materiAjar->tipe_materi === 'File' ? 'checked' : '' }}>
                                Dokumen (File Upload)
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer font-bold text-gray-700">
                                <input type="radio" name="tipe_materi" value="Link URL" class="w-5 h-5 text-[#03045E] focus:ring-[#03045E]" onchange="toggleMateriInput()" {{ $materiAjar->tipe_materi === 'Link URL' ? 'checked' : '' }}>
                                Tautan (Link/URL)
                            </label>
                        </div>

                        <div id="input-file" class="mt-4 {{ $materiAjar->tipe_materi === 'Link URL' ? 'hidden' : '' }}">
                            <label class="block text-sm font-bold text-gray-600 mb-2">Unggah Dokumen Baru (Max 10MB) — Kosongkan jika tidak ingin mengubah</label>
                            @if($materiAjar->tipe_materi === 'File' && $materiAjar->file_path)
                                <div class="mb-3">
                                    <a href="{{ asset('storage/' . $materiAjar->file_path) }}" target="_blank" class="text-sm font-bold text-[#0077B6] underline">Lihat File Saat Ini</a>
                                </div>
                            @endif
                            <input type="file" name="file_upload" class="w-full bg-white border border-gray-200 rounded-xl p-3 text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-[#03045E] file:text-white hover:file:bg-[#0077B6] cursor-pointer" accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx">
                        </div>

                        <div id="input-link" class="mt-4 {{ $materiAjar->tipe_materi === 'File' ? 'hidden' : '' }}">
                            <label class="block text-sm font-bold text-gray-600 mb-2">Alamat Tautan (URL)</label>
                            <input type="url" name="url_link" value="{{ old('url_link', $materiAjar->url_link) }}" class="w-full bg-white border border-gray-200 text-[#03045E] rounded-xl p-3 focus:ring-2 focus:ring-[#03045E]/20 focus:border-[#03045E] transition-all">
                            @error('url_link') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <button type="submit" class="w-full py-4 bg-[#03045E] text-white rounded-2xl font-bold shadow-lg hover:bg-blue-900 transition-all uppercase tracking-widest">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleMateriInput() {
            const tipe = document.querySelector('input[name="tipe_materi"]:checked').value;
            if (tipe === 'File') {
                document.getElementById('input-file').classList.remove('hidden');
                document.getElementById('input-link').classList.add('hidden');
            } else {
                document.getElementById('input-file').classList.add('hidden');
                document.getElementById('input-link').classList.remove('hidden');
            }
        }

        function tutupNotifError() {
            const notif = document.getElementById('notif-error');
            if (notif) {
                notif.style.transition = 'opacity 0.5s';
                notif.style.opacity = '0';
                setTimeout(() => notif.remove(), 500);
            }
        }
        setTimeout(tutupNotifError, 5000);
    </script>
</x-app-layout>