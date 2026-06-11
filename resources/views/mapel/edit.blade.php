<x-app-layout>
    <div class="max-w-3xl mx-auto">
        <div class="mb-8 text-center">
            <a href="{{ route('mapel.index') }}" class="text-[#03045E] font-bold flex items-center gap-2 hover:underline mb-4 uppercase tracking-widest text-xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Daftar Mapel
            </a>
            <h2 class="text-3xl font-black text-[#03045E] tracking-tight uppercase">Edit Mata Pelajaran</h2>
        </div>

        <div class="bg-white rounded-[2rem] shadow-sm p-10 border border-gray-100">
            <form action="{{ route('mapel.update', $mapel->id) }}" method="POST">
                @csrf 
                @method('PUT')
                <div class="space-y-8">
                    <div>
                        <label class="block text-xs font-black text-[#03045E] mb-3 uppercase tracking-[0.2em]">Kode Mata Pelajaran</label>
                        <input type="text" name="kode_mapel" value="{{ old('kode_mapel', $mapel->kode_mapel) }}" class="w-full rounded-2xl border-gray-100 bg-gray-50 p-4 focus:ring-2 focus:ring-[#03045E] focus:bg-white transition-all font-bold">
                        @error('kode_mapel') <p class="text-red-500 text-xs mt-2 font-bold">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-black text-[#03045E] mb-3 uppercase tracking-[0.2em]">Nama Mata Pelajaran</label>
                        <input type="text" name="nama_mapel" value="{{ old('nama_mapel', $mapel->nama_mapel) }}" class="w-full rounded-2xl border-gray-100 bg-gray-50 p-4 focus:ring-2 focus:ring-[#03045E] focus:bg-white transition-all font-bold">
                        @error('nama_mapel') <p class="text-red-500 text-xs mt-2 font-bold">{{ $message }}</p> @enderror
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full py-5 bg-amber-500 text-white rounded-full font-black shadow-xl hover:bg-amber-600 transition-all uppercase tracking-[0.3em] text-sm">
                            Perbarui Mata Pelajaran
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>