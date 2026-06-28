<x-app-layout>
    <div class="max-w-2xl mx-auto"> 
        <div class="mb-8">
            <div class="flex items-center gap-2 text-sm text-gray-500 mb-2">
                <a href="{{ route('dashboard') }}" class="hover:text-[#03045E] font-medium">Dashboard</a>
                <span>›</span>
                <a href="{{ route('materi-ajar.index') }}" class="hover:text-[#03045E] font-medium">Materi Ajar</a>
                <span>›</span>
                <span class="text-[#03045E] font-bold">Unggah Materi Baru</span>
            </div>
            <a href="{{ route('materi-ajar.index') }}" class="flex items-center gap-2 text-sm text-gray-500 hover:text-[#03045E] font-medium mb-4">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali
            </a>
            <h2 class="text-3xl font-black text-[#03045E] tracking-tight">Unggah Materi Baru</h2>
            {{-- tambah deskripsi: --}}
            <p class="text-gray-500 text-sm mt-1">Tambahkan dokumen atau tautan materi pembelajaran.</p>
        </div>

        <div class="bg-white rounded-[2rem] shadow-sm p-8 border border-gray-100 max-w-2xl">
        
        @if($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-400 text-red-700 rounded-2xl">
                <div class="flex items-center gap-2 mb-2">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M12 3a9 9 0 100 18A9 9 0 0012 3z"/>
                    </svg>
                    <span class="font-bold">Proses Presensi Dibatalkan, terdapat {{ $errors->count() }} kesalahan:</span>
                </div>
                <ul class="list-disc list-inside space-y-1 text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

            <form action="{{ route('materi-ajar.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                
                <div>
                    <label class="block text-sm font-bold text-[#03045E] mb-2 uppercase tracking-wide">Judul Materi</label>
                    <input type="text" name="judul" class="w-full bg-gray-50 border border-gray-200 text-[#03045E] rounded-2xl p-4 focus:ring-4 focus:ring-[#03045E]/20 focus:border-[#03045E] transition-all font-medium" placeholder="Misal: Modul Aljabar Dasar Pertemuan 1">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-[#03045E] mb-2 uppercase tracking-wide">Mata Pelajaran</label>
                        <select name="mapel_id" class="w-full bg-gray-50 border border-gray-200 text-[#03045E] rounded-2xl p-4 focus:ring-4 focus:ring-[#03045E]/20 focus:border-[#03045E] transition-all font-medium">
                            <option value="">-- Pilih Mapel --</option>
                            @foreach($mapels as $mapel)
                                <option value="{{ $mapel->id }}">{{ $mapel->nama_mapel }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-[#03045E] mb-2 uppercase tracking-wide">Kelas Target</label>
                        <select name="kelas_id" class="w-full bg-gray-50 border border-gray-200 text-[#03045E] rounded-2xl p-4 focus:ring-4 focus:ring-[#03045E]/20 focus:border-[#03045E] transition-all font-medium">
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($kelas as $k)
                                <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-[#03045E] mb-2 uppercase tracking-wide">Deskripsi / Instruksi (Opsional)</label>
                    <textarea name="deskripsi" rows="3" class="w-full bg-gray-50 border border-gray-200 text-[#03045E] rounded-2xl p-4 focus:ring-4 focus:ring-[#03045E]/20 focus:border-[#03045E] transition-all font-medium" placeholder="Tulis instruksi singkat untuk materi ini..."></textarea>
                </div>

                <div class="p-5 border-2 border-dashed border-gray-300 rounded-2xl bg-gray-50">
                    <label class="block text-sm font-bold text-[#03045E] mb-4 uppercase tracking-wide">Tipe Materi</label>
                    
                    <div class="flex gap-6 mb-4">
                        <label class="flex items-center gap-2 cursor-pointer font-bold text-gray-700">
                            <input type="radio" name="tipe_materi" value="File" class="w-5 h-5 text-[#03045E] focus:ring-[#03045E]" checked onchange="toggleMateriInput()">
                            Dokumen (File Upload)
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer font-bold text-gray-700">
                            <input type="radio" name="tipe_materi" value="Link URL" class="w-5 h-5 text-[#03045E] focus:ring-[#03045E]" onchange="toggleMateriInput()">
                            Tautan (Link/URL)
                        </label>
                    </div>

                    <div id="input-file" class="mt-4">
                        <label class="block text-sm font-bold text-gray-600 mb-2">Unggah Dokumen (Max 10MB)</label>
                        <input type="file" name="file_upload" class="w-full bg-white border border-gray-200 rounded-xl p-3 text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-[#03045E] file:text-white hover:file:bg-[#0077B6] cursor-pointer" accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx">
                        <p class="text-xs text-gray-400 mt-2">Format didukung: PDF, Word, Excel, PowerPoint.</p>
                    </div>

                    <div id="input-link" class="mt-4 hidden">
                        <label class="block text-sm font-bold text-gray-600 mb-2">Alamat Tautan (URL)</label>
                        <input type="url" name="url_link" class="w-full bg-white border border-gray-200 text-[#03045E] rounded-xl p-3 focus:ring-2 focus:ring-[#03045E]/20 focus:border-[#03045E] transition-all" placeholder="https://youtube.com/... atau https://drive.google.com/...">
                    </div>
                </div>

                <div class="pt-4 flex gap-4">
                    <button type="submit" class="px-8 py-4 bg-[#03045E] text-white rounded-full font-bold shadow-lg hover:scale-105 transition-all">SIMPAN MATERI</button>
                    <a href="{{ route('materi-ajar.index') }}" class="px-8 py-4 bg-gray-200 text-gray-600 rounded-full font-bold hover:bg-gray-300 transition-all">BATAL</a>
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
    </script>
</x-app-layout>
