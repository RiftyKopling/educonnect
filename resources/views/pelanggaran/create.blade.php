<x-app-layout>
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-4">
        <a href="{{ route('pelanggaran.index') }}" class="hover:text-[#03045E] font-medium flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Master Pelanggaran
        </a>
        <span>›</span>
        <span class="text-[#03045E] font-bold">Tambah Baru</span>
    </div>

    <div class="mb-6">
        <h2 class="text-3xl font-black text-[#03045E] tracking-tight uppercase">Tambah Pelanggaran</h2>
    </div>

    <div class="bg-white rounded-[2rem] shadow-sm p-8 border border-gray-100 max-w-2xl">
        <form action="{{ route('pelanggaran.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div>
                <label class="block text-sm font-bold text-[#03045E] mb-2 uppercase tracking-wide">Nama Pelanggaran</label>
                <input type="text" name="nama_pelanggaran" required class="w-full bg-gray-50 border border-gray-200 text-[#03045E] rounded-2xl p-4 focus:ring-4 focus:ring-[#03045E]/20 focus:border-[#03045E] transition-all font-medium" placeholder="Contoh: Terlambat Masuk Sekolah">
            </div>

            <div>
                <label class="block text-sm font-bold text-[#03045E] mb-2 uppercase tracking-wide">Kategori</label>
                <select name="kategori" required class="w-full bg-gray-50 border border-gray-200 text-[#03045E] rounded-2xl p-4 focus:ring-4 focus:ring-[#03045E]/20 focus:border-[#03045E] transition-all font-medium">
                    <option value="Ringan">Ringan</option>
                    <option value="Sedang">Sedang</option>
                    <option value="Berat">Berat</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-bold text-[#03045E] mb-2 uppercase tracking-wide">Deskripsi (Opsional)</label>
                <textarea name="deskripsi" rows="3" class="w-full bg-gray-50 border border-gray-200 text-[#03045E] rounded-2xl p-4 focus:ring-4 focus:ring-[#03045E]/20 focus:border-[#03045E] transition-all font-medium"></textarea>
            </div>

            <div class="pt-4 flex gap-4">
                <button type="submit" class="px-8 py-4 bg-[#03045E] text-white rounded-full font-bold shadow-lg hover:scale-105 transition-all">SIMPAN DATA</button>
                <a href="{{ route('pelanggaran.index') }}" class="px-8 py-4 bg-gray-100 text-gray-500 rounded-full font-bold hover:bg-gray-200 transition-all">BATAL</a>
            </div>
        </form>
    </div>
</x-app-layout>
