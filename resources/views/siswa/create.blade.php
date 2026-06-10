<x-app-layout>
    <div class="max-w-4xl mx-auto">
        <div class="mb-8">
            <a href="{{ route('siswa.index') }}" class="text-[#03045E] font-bold flex items-center gap-2 mb-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Daftar
            </a>
            <h2 class="text-3xl font-black text-[#03045E] tracking-tight">Registrasi Siswa Baru</h2>
        </div>

        <!-- error handling -->
        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-100 text-red-700 rounded-2xl">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-[2rem] shadow-sm p-10 border border-gray-100">
            <form action="{{ route('siswa.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- NISN -->
                    <div class="col-span-1">
                        <label class="block text-sm font-bold text-[#03045E] mb-2 uppercase">NISN (10 Digit)</label>
                        <input type="text" name="nisn" maxlength="10" class="w-full rounded-2xl border-gray-200 p-4 focus:ring-[#03045E]" placeholder="Contoh: 0012345678">
                    </div>

                    <!-- Nama -->
                    <div class="col-span-1">
                        <label class="block text-sm font-bold text-[#03045E] mb-2 uppercase">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" class="w-full rounded-2xl border-gray-200 p-4 focus:ring-[#03045E]" placeholder="Nama sesuai ijazah...">
                    </div>

                    <!-- Jenis Kelamin -->
                    <div>
                        <label class="block text-sm font-bold text-[#03045E] mb-2 uppercase">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="w-full rounded-2xl border-gray-200 p-4">
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>

                    <!-- Kelas -->
                    <div>
                        <label class="block text-sm font-bold text-[#03045E] mb-2 uppercase">Pilih Kelas</label>
                        <select name="kelas_id" class="w-full rounded-2xl border-gray-200 p-4">
                            @foreach($data_kelas as $k)
                                <option value="{{ $k->id }}">{{ $k->nama_kelas }} (Tingkat {{ $k->tingkat }})</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- TTL -->
                    <div>
                        <label class="block text-sm font-bold text-[#03045E] mb-2 uppercase">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" class="w-full rounded-2xl border-gray-200 p-4">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-[#03045E] mb-2 uppercase">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" class="w-full rounded-2xl border-gray-200 p-4">
                    </div>

                    <!-- Alamat -->
                    <div class="col-span-full">
                        <label class="block text-sm font-bold text-[#03045E] mb-2 uppercase">Alamat Lengkap</label>
                        <textarea name="alamat" rows="3" class="w-full rounded-2xl border-gray-200 p-4"></textarea>
                    </div>
                </div>

                <div class="mt-10">
                    <button type="submit" class="w-full py-4 bg-[#03045E] text-white rounded-2xl font-bold shadow-lg uppercase tracking-widest hover:bg-blue-900 transition-all">
                        Simpan Data Siswa
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>