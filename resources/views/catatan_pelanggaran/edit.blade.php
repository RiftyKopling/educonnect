<x-app-layout>
    <div class="max-w-3xl mx-auto">
        <div class="mb-8">
            <div class="flex items-center gap-2 text-sm text-gray-500 mb-2">
                <a href="{{ route('dashboard') }}" class="hover:text-[#03045E] font-medium">Dashboard</a>
                <span>›</span>
                <a href="{{ route('catatan-pelanggaran.index') }}" class="hover:text-[#03045E] font-medium">Catatan Pelanggaran</a>
                <span>›</span>
                <span class="text-[#03045E] font-bold">Edit Catatan</span>
            </div>
            <a href="{{ route('catatan-pelanggaran.index') }}" class="flex items-center gap-2 text-sm text-gray-500 hover:text-[#03045E] font-medium mb-4">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali
            </a>
            <h2 class="text-3xl font-black text-[#03045E] tracking-tight">Edit Catatan Pelanggaran</h2>
            <p class="text-gray-500 text-sm mt-1">Perbarui data catatan pelanggaran siswa.</p>
        </div>

        <div class="bg-white rounded-[2rem] shadow-sm p-8 border border-gray-100">
            <form action="{{ route('catatan-pelanggaran.update', $catatanPelanggaran->id) }}" method="POST" class="space-y-6">
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
                            <span class="font-bold">Terdapat {{ $errors->count() }} kesalahan:</span>
                        </div>
                        <ul class="list-disc list-inside space-y-1 text-sm">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div>
                    <label class="block text-sm font-bold text-[#03045E] mb-2 uppercase tracking-widest">Tanggal Insiden</label>
                    <input type="date" name="tanggal"
                        value="{{ old('tanggal', $catatanPelanggaran->tanggal) }}"
                        class="w-full bg-gray-50 border border-gray-200 text-[#03045E] rounded-2xl p-4 focus:ring-4 focus:ring-[#03045E]/20 focus:border-[#03045E] transition-all font-medium">
                    @error('tanggal') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-[#03045E] mb-2 uppercase tracking-widest">Pilih Kelas</label>
                    <select id="kelas_id" class="w-full bg-gray-50 border border-gray-200 text-[#03045E] rounded-2xl p-4 focus:ring-4 focus:ring-[#03045E]/20 focus:border-[#03045E] transition-all font-medium">
                        <option value="">-- Pilih Kelas Terlebih Dahulu --</option>
                        @foreach($kelasList as $kelas)
                            <option value="{{ $kelas->id }}" {{ $catatanPelanggaran->siswa->kelas_id == $kelas->id ? 'selected' : '' }}>
                                {{ $kelas->nama_kelas }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-[#03045E] mb-2 uppercase tracking-widest">Pilih Siswa</label>
                    <select id="siswa_nisn" name="siswa_nisn"
                        class="w-full bg-gray-50 border border-gray-200 text-[#03045E] rounded-2xl p-4 focus:ring-4 focus:ring-[#03045E]/20 focus:border-[#03045E] transition-all font-medium">
                        <option value="{{ $catatanPelanggaran->siswa_nisn }}">
                            {{ $catatanPelanggaran->siswa->nama_lengkap }} ({{ $catatanPelanggaran->siswa_nisn }})
                        </option>
                    </select>
                    @error('siswa_nisn') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-[#03045E] mb-2 uppercase tracking-widest">Jenis Pelanggaran</label>
                    <select name="pelanggaran_id" 
                        class="w-full bg-gray-50 border border-gray-200 text-[#03045E] rounded-2xl p-4 focus:ring-4 focus:ring-[#03045E]/20 focus:border-[#03045E] transition-all font-medium">
                        <option value="">-- Pilih Pelanggaran --</option>
                        @foreach($pelanggarans as $pelanggaran)
                            <option value="{{ $pelanggaran->id }}" {{ old('pelanggaran_id', $catatanPelanggaran->pelanggaran_id) == $pelanggaran->id ? 'selected' : '' }}>
                                [{{ $pelanggaran->kategori }}] {{ $pelanggaran->nama_pelanggaran }}
                            </option>
                        @endforeach
                    </select>
                    @error('pelanggaran_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-[#03045E] mb-2 uppercase tracking-widest">Keterangan Detail</label>
                    <textarea name="keterangan" rows="4"
                        class="w-full bg-gray-50 border border-gray-200 text-[#03045E] rounded-2xl p-4 focus:ring-4 focus:ring-[#03045E]/20 focus:border-[#03045E] transition-all font-medium"
                        placeholder="Tuliskan detail insiden secara deskriptif...">{{ old('keterangan', $catatanPelanggaran->keterangan) }}</textarea>
                    @error('keterangan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <button type="submit" class="w-full py-4 bg-[#03045E] text-white rounded-2xl font-bold shadow-lg hover:bg-blue-900 transition-all uppercase tracking-widest">
                    Simpan Perubahan
                </button>
            </form>
        </div>
    </div>

    <script>
        // Load siswa saat kelas berubah
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
                            const selected = siswa.nisn === '{{ $catatanPelanggaran->siswa_nisn }}' ? 'selected' : '';
                            siswaDropdown.innerHTML += `<option value="${siswa.nisn}" ${selected}>${siswa.nama_lengkap} (${siswa.nisn})</option>`;
                        });
                        siswaDropdown.disabled = false;
                    })
                    .catch(error => {
                        console.error('Error fetching data:', error);
                        siswaDropdown.innerHTML = '<option value="">Gagal memuat data</option>';
                    });
            } else {
                siswaDropdown.innerHTML = '<option value="">-- Pilih Siswa --</option>';
                siswaDropdown.disabled = false;
            }
        });

        // Trigger load siswa otomatis saat halaman dibuka
        window.addEventListener('DOMContentLoaded', function() {
            const kelasSelect = document.getElementById('kelas_id');
            if (kelasSelect.value) {
                kelasSelect.dispatchEvent(new Event('change'));
            }
        });

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