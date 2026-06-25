<x-app-layout>
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-4">
        <a href="{{ route('materi-ajar.index') }}" class="hover:text-[#03045E] font-medium flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Materi Ajar
        </a>
        <span>›</span>
        <span class="text-[#03045E] font-bold">Edit Materi</span>
    </div>

    <div class="mb-6">
        <h2 class="text-3xl font-black text-[#03045E] tracking-tight uppercase">Perbarui Materi</h2>
    </div>

    <div class="bg-white rounded-[2rem] shadow-sm p-8 border border-gray-100 max-w-2xl">
        
        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 text-red-600 rounded-xl font-medium text-sm">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('materi-ajar.update', $materiAjar->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div>
                <label class="block text-sm font-bold text-[#03045E] mb-2 uppercase tracking-wide">Judul Materi</label>
                <input type="text" name="judul" value="{{ old('judul', $materiAjar->judul) }}" required class="w-full bg-gray-50 border border-gray-200 text-[#03045E] rounded-2xl p-4 focus:ring-4 focus:ring-[#03045E]/20 focus:border-[#03045E] transition-all font-medium">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-[#03045E] mb-2 uppercase tracking-wide">Mata Pelajaran</label>
                    <select name="mapel_id" required class="w-full bg-gray-50 border border-gray-200 text-[#03045E] rounded-2xl p-4 focus:ring-4 focus:ring-[#03045E]/20 focus:border-[#03045E] transition-all font-medium">
                        <option value="">-- Pilih Mapel --</option>
                        @foreach($mapels as $mapel)
                            <option value="{{ $mapel->id }}" {{ $materiAjar->mapel_id == $mapel->id ? 'selected' : '' }}>{{ $mapel->nama_mapel }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-[#03045E] mb-2 uppercase tracking-wide">Kelas Target</label>
                    <select name="kelas_id" required class="w-full bg-gray-50 border border-gray-200 text-[#03045E] rounded-2xl p-4 focus:ring-4 focus:ring-[#03045E]/20 focus:border-[#03045E] transition-all font-medium">
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($kelas as $k)
                            <option value="{{ $k->id }}" {{ $materiAjar->kelas_id == $k->id ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-[#03045E] mb-2 uppercase tracking-wide">Deskripsi / Instruksi (Opsional)</label>
                <textarea name="deskripsi" rows="3" class="w-full bg-gray-50 border border-gray-200 text-[#03045E] rounded-2xl p-4 focus:ring-4 focus:ring-[#03045E]/20 focus:border-[#03045E] transition-all font-medium">{{ old('deskripsi', $materiAjar->deskripsi) }}</textarea>
            </div>

            <div class="p-5 border-2 border-dashed border-gray-300 rounded-2xl bg-gray-50">
                <label class="block text-sm font-bold text-[#03045E] mb-4 uppercase tracking-wide">Tipe Materi</label>
                
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
                    <label class="block text-sm font-bold text-gray-600 mb-2">Unggah Dokumen Baru (Max 10MB) - Kosongkan jika tidak ingin mengubah</label>
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
                </div>
            </div>

            <div class="pt-4 flex gap-4">
                <button type="submit" class="px-8 py-4 bg-[#03045E] text-white rounded-full font-bold shadow-lg hover:scale-105 transition-all">SIMPAN PERUBAHAN</button>
                <a href="{{ route('materi-ajar.index') }}" class="px-8 py-4 bg-gray-200 text-gray-600 rounded-full font-bold hover:bg-gray-300 transition-all">BATAL</a>
            </div>
        </form>
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
    </script>
</x-app-layout>
