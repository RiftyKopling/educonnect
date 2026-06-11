<x-app-layout>
    <div class="max-w-4xl mx-auto">
        <div class="mb-8">
            <a href="{{ route('kelas.index') }}" class="text-[#03045E] font-bold flex items-center gap-2 hover:underline mb-4 uppercase tracking-widest text-xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Daftar Kelas
            </a>
            <h2 class="text-3xl font-black text-[#03045E] tracking-tight uppercase">Edit Kelas: {{ $kelas->nama_kelas }}</h2>
        </div>

        <div class="bg-white rounded-[2rem] shadow-sm p-10 border border-gray-100">
            <form action="{{ route('kelas.update', $kelas->id) }}" method="POST">
                @csrf @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="block text-xs font-black text-[#03045E] mb-3 uppercase tracking-[0.2em]">Nama Kelas</label>
                        <input type="text" name="nama_kelas" value="{{ old('nama_kelas', $kelas->nama_kelas) }}" class="w-full rounded-2xl border-gray-100 bg-gray-50 p-4 focus:ring-2 focus:ring-[#03045E] focus:bg-white transition-all font-bold">
                        @error('nama_kelas') <p class="text-red-500 text-xs mt-2 font-bold">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-black text-[#03045E] mb-3 uppercase tracking-[0.2em]">Tingkat</label>
                        <select name="tingkat" class="w-full rounded-2xl border-gray-100 bg-gray-50 p-4 focus:ring-2 focus:ring-[#03045E] focus:bg-white transition-all font-bold">
                            @foreach([7,8,9] as $t)
                                <option value="{{ $t }}" {{ old('tingkat', $kelas->tingkat) == $t ? 'selected' : '' }}>Tingkat {{ $t }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-black text-[#03045E] mb-3 uppercase tracking-[0.2em]">Tahun Ajaran</label>
                        <input type="text" name="tahun_ajaran" value="{{ old('tahun_ajaran', $kelas->tahun_ajaran) }}" class="w-full rounded-2xl border-gray-100 bg-gray-50 p-4 focus:ring-2 focus:ring-[#03045E] focus:bg-white transition-all font-bold">
                        @error('tahun_ajaran') <p class="text-red-500 text-xs mt-2 font-bold">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-black text-[#03045E] mb-3 uppercase tracking-[0.2em]">Ganti Wali Kelas</label>
                        <select name="wali_kelas_id" class="w-full rounded-2xl border-gray-100 bg-gray-50 p-4 focus:ring-2 focus:ring-[#03045E] focus:bg-white transition-all font-bold">
                            <option value="">-- Belum ada Wali Kelas --</option>
                            @foreach($data_guru as $guru)
                                <option value="{{ $guru->id }}" {{ old('wali_kelas_id', $kelas->wali_kelas_id) == $guru->id ? 'selected' : '' }}>{{ $guru->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mt-12">
                    <button type="submit" class="w-full py-5 bg-amber-500 text-white rounded-full font-black shadow-xl hover:bg-amber-600 transition-all uppercase tracking-[0.3em] text-sm">
                        Perbarui Data Kelas
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>