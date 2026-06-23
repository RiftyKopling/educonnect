<x-app-layout>
    <div class="max-w-3xl mx-auto">
        <div class="mb-8">
            <div class="flex items-center gap-2 text-sm text-gray-500 mb-2">
                <a href="{{ route('dashboard') }}" class="hover:text-[#03045E] font-medium">
                    Dashboard
                </a>
                <span>›</span>
                <a href="{{ route('mapel.index') }}" class="hover:text-[#03045E] font-medium">Manajemen Mata Pelajaran</a>
                <span>›</span>
                <span class="text-[#03045E] font-bold">Tambah Mata Pelajaran</span>
            </div>
            <a href="{{ route('mapel.index') }}" class="flex items-center gap-2 text-sm text-gray-500 hover:text-[#03045E] font-medium mb-4">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali
            </a>
            <h2 class="text-3xl font-black text-[#03045E] tracking-tight">Tambah Mata Pelajaran</h2>
            <p class="text-gray-500 text-sm mt-1">Tambahkan mata pelajaran baru ke dalam kurikulum sekolah</p>
        </div>

        <div class="bg-white rounded-[2rem] shadow-sm p-8 border border-gray-100">
            <form action="{{ route('mapel.store') }}" method="POST">
                @if($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border border-red-400 text-red-700 rounded-2xl">
                        <div class="flex items-center gap-2 mb-2">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M12 3a9 9 0 100 18A9 9 0 0012 3z"/>
                            </svg>
                            <span class="font-bold">Proses Tambah Mata Pelajaran Dibatalkan, terdapat {{ $errors->count() }} kesalahan:</span>
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
                    <!-- Kode Mata Pelajaran (Dropdown) -->
                    <div>
                        <label class="block text-sm font-bold text-[#03045E] mb-2 uppercase tracking-widest">Kode Mata Pelajaran</label>
                        <select name="kode_mapel" class="w-full rounded-2xl border-gray-200 focus:ring-[#03045E] focus:border-[#03045E] p-4">
                            <option value="">Pilih Kode Mata Pelajaran...</option>
                            <option value="AGM" {{ old('kode_mapel') == 'AGM' ? 'selected' : '' }}>AGM - Agama</option>
                            <option value="BIN" {{ old('kode_mapel') == 'BIN' ? 'selected' : '' }}>BIN - Bahasa Indonesia</option>
                            <option value="BIG" {{ old('kode_mapel') == 'BIG' ? 'selected' : '' }}>BIG - Bahasa Inggris</option>
                            <option value="BDG" {{ old('kode_mapel') == 'BDG' ? 'selected' : '' }}>BDG - Bahasa Daerah</option>
                            <option value="MAT" {{ old('kode_mapel') == 'MAT' ? 'selected' : '' }}>MAT - Matematika</option>
                            <option value="IPA" {{ old('kode_mapel') == 'IPA' ? 'selected' : '' }}>IPA - Ilmu Pengetahuan Alam</option>
                            <option value="IPS" {{ old('kode_mapel') == 'IPS' ? 'selected' : '' }}>IPS - Ilmu Pengetahuan Sosial</option>
                            <option value="PKN" {{ old('kode_mapel') == 'PKN' ? 'selected' : '' }}>PKN - Pendidikan Pancasila & Kewarganegaraan</option>
                            <option value="SEN" {{ old('kode_mapel') == 'SEN' ? 'selected' : '' }}>SEN - Seni Budaya</option>
                            <option value="PJO" {{ old('kode_mapel') == 'PJO' ? 'selected' : '' }}>PJO - Pendidikan Jasmani & Olahraga</option>
                            <option value="PRA" {{ old('kode_mapel') == 'PRA' ? 'selected' : '' }}>PRA - Prakarya</option>
                            <option value="BK" {{ old('kode_mapel') == 'BK' ? 'selected' : '' }}>BK - Bimbingan Konseling</option>
                            <option value="MUL" {{ old('kode_mapel') == 'MUL' ? 'selected' : '' }}>MUL - Muatan Lokal</option>
                            <option value="TIK" {{ old('kode_mapel') == 'TIK' ? 'selected' : '' }}>TIK - Teknologi Informasi & Komunikasi</option>
                            <option value="KIM" {{ old('kode_mapel') == 'KIM' ? 'selected' : '' }}>KIM - Kimia</option>
                            <option value="FIS" {{ old('kode_mapel') == 'FIS' ? 'selected' : '' }}>FIS - Fisika</option>
                            <option value="BIO" {{ old('kode_mapel') == 'BIO' ? 'selected' : '' }}>BIO - Biologi</option>
                            <option value="EKO" {{ old('kode_mapel') == 'EKO' ? 'selected' : '' }}>EKO - Ekonomi</option>
                            <option value="GEO" {{ old('kode_mapel') == 'GEO' ? 'selected' : '' }}>GEO - Geografi</option>
                            <option value="SEJ" {{ old('kode_mapel') == 'SEJ' ? 'selected' : '' }}>SEJ - Sejarah</option>
                        </select>
                        @error('kode_mapel') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Nama Mata Pelajaran -->
                    <div>
                        <label class="block text-sm font-bold text-[#03045E] mb-2 uppercase tracking-widest">Nama Mata Pelajaran</label>
                        <input type="text" 
                               name="nama_mapel" 
                               value="{{ old('nama_mapel') }}" 
                               class="w-full rounded-2xl border-gray-200 focus:ring-[#03045E] focus:border-[#03045E] p-4" 
                               placeholder="Contoh: Ilmu Pengetahuan Alam" 
                               required>
                        @error('nama_mapel') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Tombol Submit -->
                    <div class="pt-2">
                        <button type="submit" 
                                class="w-full py-4 bg-[#03045E] text-white rounded-2xl font-bold shadow-lg hover:bg-blue-900 transition-all uppercase tracking-widest">
                            Simpan Mata Pelajaran
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>