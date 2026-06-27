<x-app-layout>
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-4">
        <a href="{{ route('catatan-pelanggaran.index') }}" class="hover:text-[#03045E] font-medium flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Catatan Pelanggaran
        </a>
        <span>›</span>
        <span class="text-[#03045E] font-bold">Catat Baru</span>
    </div>

    <div class="mb-6">
        <h2 class="text-3xl font-black text-[#03045E] tracking-tight uppercase">Catat Pelanggaran Siswa</h2>
    </div>

    <div class="bg-white rounded-[2rem] shadow-sm p-8 border border-gray-100 max-w-2xl">
        <form action="{{ route('catatan-pelanggaran.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div>
                <label class="block text-sm font-bold text-[#03045E] mb-2 uppercase tracking-wide">Tanggal Insiden</label>
                <input type="date" name="tanggal" required class="w-full bg-gray-50 border border-gray-200 text-[#03045E] rounded-2xl p-4 focus:ring-4 focus:ring-[#03045E]/20 focus:border-[#03045E] transition-all font-medium" value="{{ date('Y-m-d') }}">
            </div>

            <div>
                <label class="block text-sm font-bold text-[#03045E] mb-2 uppercase tracking-wide">Pilih Kelas</label>
                <select id="kelas_id" class="w-full bg-gray-50 border border-gray-200 text-[#03045E] rounded-2xl p-4 focus:ring-4 focus:ring-[#03045E]/20 focus:border-[#03045E] transition-all font-medium">
                    <option value="">-- Pilih Kelas Terlebih Dahulu --</option>
                    @foreach($kelasList as $kelas)
                        <option value="{{ $kelas->id }}">{{ $kelas->nama_kelas }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-bold text-[#03045E] mb-2 uppercase tracking-wide">Pilih Siswa</label>
                <select id="siswa_nisn" name="siswa_nisn" required class="w-full bg-gray-50 border border-gray-200 text-[#03045E] rounded-2xl p-4 focus:ring-4 focus:ring-[#03045E]/20 focus:border-[#03045E] transition-all font-medium" disabled>
                    <option value="">-- Pilih Siswa --</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-bold text-[#03045E] mb-2 uppercase tracking-wide">Jenis Pelanggaran</label>
                <select name="pelanggaran_id" required class="w-full bg-gray-50 border border-gray-200 text-[#03045E] rounded-2xl p-4 focus:ring-4 focus:ring-[#03045E]/20 focus:border-[#03045E] transition-all font-medium">
                    <option value="">-- Pilih Pelanggaran --</option>
                    @foreach($pelanggarans as $pelanggaran)
                        <option value="{{ $pelanggaran->id }}">[{{ $pelanggaran->kategori }}] {{ $pelanggaran->nama_pelanggaran }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-bold text-[#03045E] mb-2 uppercase tracking-wide">Keterangan Detail</label>
                <textarea name="keterangan" rows="4" class="w-full bg-gray-50 border border-gray-200 text-[#03045E] rounded-2xl p-4 focus:ring-4 focus:ring-[#03045E]/20 focus:border-[#03045E] transition-all font-medium" placeholder="Tuliskan detail insiden secara deskriptif..."></textarea>
            </div>

            <div class="pt-4 flex gap-4">
                <button type="submit" class="px-8 py-4 bg-[#03045E] text-white rounded-full font-bold shadow-lg hover:scale-105 transition-all">SIMPAN DATA</button>
                <a href="{{ route('catatan-pelanggaran.index') }}" class="px-8 py-4 bg-gray-100 text-gray-500 rounded-full font-bold hover:bg-gray-200 transition-all">BATAL</a>
            </div>
        </form>
    </div>

    <!-- Script for Cascading Dropdown -->
    <script>
        document.getElementById('kelas_id').addEventListener('change', function() {
            let kelasId = this.value;
            let siswaDropdown = document.getElementById('siswa_nisn');
            
            siswaDropdown.innerHTML = '<option value="">Memuat data...</option>';
            siswaDropdown.disabled = true;

            if (kelasId) {
                fetch(`/api/kelas/${kelasId}/siswa`)
                    .then(response => response.json())
                    .then(data => {
                        siswaDropdown.innerHTML = '<option value="">-- Pilih Siswa --</option>';
                        data.forEach(siswa => {
                            siswaDropdown.innerHTML += `<option value="${siswa.nisn}">${siswa.nama_lengkap} (${siswa.nisn})</option>`;
                        });
                        siswaDropdown.disabled = false;
                    })
                    .catch(error => {
                        console.error('Error fetching data:', error);
                        siswaDropdown.innerHTML = '<option value="">Gagal memuat data</option>';
                    });
            } else {
                siswaDropdown.innerHTML = '<option value="">-- Pilih Siswa --</option>';
            }
        });
    </script>
</x-app-layout>
