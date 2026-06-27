<x-app-layout>
    <div class="max-w-3xl mx-auto">
        <div class="mb-8">
            <div class="flex items-center gap-2 text-sm text-gray-500 mb-2">
                <a href="{{ route('dashboard') }}" class="hover:text-[#03045E] font-medium">Dashboard</a>
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
                @csrf

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

                @if(session('error'))
                    <div class="mb-6 p-4 bg-red-50 border border-red-400 text-red-700 rounded-2xl">
                        <span class="font-bold">{{ session('error') }}</span>
                    </div>
                @endif

                <div class="space-y-6">
                    <!-- Kode Mata Pelajaran -->
                    <div>
                        <label class="block text-sm font-bold text-[#03045E] mb-2 uppercase tracking-widest">Kode Mata Pelajaran</label>
                        <select name="kode_mapel" id="kode_mapel" onchange="isiNamaMapel(this.value)"
                            class="w-full rounded-2xl border-gray-200 focus:ring-[#03045E] focus:border-[#03045E] p-4">
                            <option value="">Pilih Kode Mata Pelajaran...</option>
                            <option value="IPA-01"   data-nama="Ilmu Pengetahuan Alam"                              {{ old('kode_mapel') == 'IPA-01'   ? 'selected' : '' }}>IPA-01 — Ilmu Pengetahuan Alam</option>
                            <option value="IPA-T-01" data-nama="Ilmu Pengetahuan Alam Terpadu"                      {{ old('kode_mapel') == 'IPA-T-01' ? 'selected' : '' }}>IPA-T-01 — Ilmu Pengetahuan Alam Terpadu</option>
                            <option value="IPS-01"   data-nama="Ilmu Pengetahuan Sosial"                            {{ old('kode_mapel') == 'IPS-01'   ? 'selected' : '' }}>IPS-01 — Ilmu Pengetahuan Sosial</option>
                            <option value="IPS-T-01" data-nama="Ilmu Pengetahuan Sosial Terpadu"                    {{ old('kode_mapel') == 'IPS-T-01' ? 'selected' : '' }}>IPS-T-01 — Ilmu Pengetahuan Sosial Terpadu</option>
                            <option value="BK-01"    data-nama="Bimbingan Konseling"                                {{ old('kode_mapel') == 'BK-01'    ? 'selected' : '' }}>BK-01 — Bimbingan Konseling</option>
                            <option value="BIN-01"   data-nama="Bahasa Indonesia"                                   {{ old('kode_mapel') == 'BIN-01'   ? 'selected' : '' }}>BIN-01 — Bahasa Indonesia</option>
                            <option value="PPKN-01"  data-nama="Pendidikan Pancasila dan Kewarganegaraan"           {{ old('kode_mapel') == 'PPKN-01'  ? 'selected' : '' }}>PPKN-01 — Pendidikan Pancasila dan Kewarganegaraan</option>
                            <option value="BING-01"  data-nama="Bahasa Inggris"                                     {{ old('kode_mapel') == 'BING-01'  ? 'selected' : '' }}>BING-01 — Bahasa Inggris</option>
                            <option value="P5-01"    data-nama="Projek Penguatan Profil Pelajar Pancasila"          {{ old('kode_mapel') == 'P5-01'    ? 'selected' : '' }}>P5-01 — Projek Penguatan Profil Pelajar Pancasila</option>
                            <option value="PJOK-01"  data-nama="Pendidikan Jasmani, Olahraga, dan Kesehatan"       {{ old('kode_mapel') == 'PJOK-01'  ? 'selected' : '' }}>PJOK-01 — Pendidikan Jasmani, Olahraga, dan Kesehatan</option>
                            <option value="MTK-01"   data-nama="Matematika"                                         {{ old('kode_mapel') == 'MTK-01'   ? 'selected' : '' }}>MTK-01 — Matematika</option>
                            <option value="BJA-01"   data-nama="Bahasa Jawa"                                        {{ old('kode_mapel') == 'BJA-01'   ? 'selected' : '' }}>BJA-01 — Bahasa Jawa</option>
                            <option value="PAI-01"   data-nama="Pendidikan Agama Islam"                             {{ old('kode_mapel') == 'PAI-01'   ? 'selected' : '' }}>PAI-01 — Pendidikan Agama Islam</option>
                            <option value="INF-01"   data-nama="Informatika"                                        {{ old('kode_mapel') == 'INF-01'   ? 'selected' : '' }}>INF-01 — Informatika</option>
                            <option value="PRA-01"   data-nama="Prakarya"                                           {{ old('kode_mapel') == 'PRA-01'   ? 'selected' : '' }}>PRA-01 — Prakarya</option>
                            <option value="TIK-01"   data-nama="Teknologi Informasi dan Komunikasi"                 {{ old('kode_mapel') == 'TIK-01'   ? 'selected' : '' }}>TIK-01 — Teknologi Informasi dan Komunikasi</option>
                            <option value="SB-01"    data-nama="Seni Budaya"                                        {{ old('kode_mapel') == 'SB-01'    ? 'selected' : '' }}>SB-01 — Seni Budaya</option>
                        </select>
                        @error('kode_mapel') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-bold text-[#03045E] mb-2 uppercase tracking-widest">Nama Mata Pelajaran</label>
                        <input type="text"
                               name="nama_mapel"
                               id="nama_mapel"
                               value="{{ old('nama_mapel') }}"
                               class="w-full rounded-2xl border-gray-200 focus:ring-[#03045E] focus:border-[#03045E] p-4"
                               placeholder="Otomatis terisi saat pilih kode dan bisa diedit">
                        @error('nama_mapel') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Tahun Ajaran -->
                    <div>
                        <label class="block text-sm font-bold text-[#03045E] mb-2 uppercase tracking-widest">Tahun Ajaran</label>
                        <select name="tahun_ajaran"
                            class="w-full rounded-2xl border-gray-200 focus:ring-[#03045E] focus:border-[#03045E] p-4">
                            <option value="">Pilih Tahun Ajaran...</option>
                            @php
                                $tahunSekarang = (int) date('Y');
                                $tahunAktif = $tahunSekarang . '/' . ($tahunSekarang + 1);
                            @endphp
                            @foreach($tahunAjaran as $tahun)
                                <option value="{{ $tahun }}" {{ (old('tahun_ajaran', $tahunAktif) == $tahun) ? 'selected' : '' }}>
                                    {{ $tahun }}{{ $tahun == $tahunAktif ? ' (Aktif)' : '' }}
                                </option>
                            @endforeach
                        </select>
                        @error('tahun_ajaran') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

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

    <script>
        const namaMapel = {
            'IPA-01'   : 'Ilmu Pengetahuan Alam',
            'IPA-T-01' : 'Ilmu Pengetahuan Alam Terpadu',
            'IPS-01'   : 'Ilmu Pengetahuan Sosial',
            'IPS-T-01' : 'Ilmu Pengetahuan Sosial Terpadu',
            'BK-01'    : 'Bimbingan Konseling',
            'BIN-01'   : 'Bahasa Indonesia',
            'PPKN-01'  : 'Pendidikan Pancasila dan Kewarganegaraan',
            'BING-01'  : 'Bahasa Inggris',
            'P5-01'    : 'Projek Penguatan Profil Pelajar Pancasila',
            'PJOK-01'  : 'Pendidikan Jasmani, Olahraga, dan Kesehatan',
            'MTK-01'   : 'Matematika',
            'BJA-01'   : 'Bahasa Jawa',
            'PAI-01'   : 'Pendidikan Agama Islam',
            'INF-01'   : 'Informatika',
            'PRA-01'   : 'Prakarya',
            'TIK-01'   : 'Teknologi Informasi dan Komunikasi',
            'SB-01'    : 'Seni Budaya',
        };

        function isiNamaMapel(kode) {
            document.getElementById('nama_mapel').value = namaMapel[kode] || '';
        }

        document.addEventListener('DOMContentLoaded', function () {
            const kode = document.getElementById('kode_mapel').value;
            if (kode) isiNamaMapel(kode);
        });
    </script>
</x-app-layout>