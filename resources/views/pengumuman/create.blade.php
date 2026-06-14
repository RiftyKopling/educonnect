<x-app-layout>
    <!-- The only way to do great work is to love what you do. - Steve Jobs -->
    <div class="max-w-3xl mx-auto">
        <div class="mb-8 text-center">
            <a href="{{ route('pengumuman.index') }}" class="text-[#03045E] font-bold flex items-center gap-2 hover:underline mb-4 uppercase tracking-widest text-xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Manajemen Pengumuman
            </a>
            <h2 class="text-3xl font-black text-[#03045E] tracking-tight uppercase">Buat Pengumuman</h2>
        </div>

        <div class="bg-white rounded-[2rem] shadow-sm p-10 border border-gray-100">
            <form action="{{ route('pengumuman.store') }}" method="POST">
                @csrf
                <div class="space-y-6">
                    <div>
                        <label class="block text-xs font-black text-[#03045E] mb-2 uppercase tracking-widest">Judul Pengumuman</label>
                        <input type="text" name="judul" value="{{ old('judul') }}" class="w-full rounded-2xl border-gray-100 bg-gray-50 p-4 focus:ring-2 focus:ring-[#03045E] focus:bg-white transition-all font-bold placeholder-gray-300" placeholder="Contoh: Jadwal Libur Semester Genap" required>
                        @error('judul') <p class="text-red-500 text-xs mt-2 font-bold">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-black text-[#03045E] mb-2 uppercase tracking-widest">Kirim Kepada</label>
                        <select name="target" class="w-full rounded-2xl border-gray-100 bg-gray-50 p-4 font-bold focus:ring-2 focus:ring-[#03045E] focus:bg-white transition-all text-[#03045E]">
                            @php $role = auth()->user()->role?->slug; @endphp
                            
                            @if($role == 'kepala-sekolah')
                                <option value="all">Seluruh Warga Sekolah (Global)</option>
                            @elseif($role == 'wali-kelas')
                                <option value="class-parents">Orang Tua Murid (Kelas {{ $kelas_diampu?->nama_kelas ?? 'Ampuan Anda' }})</option>
                                <option value="kepala-sekolah">Kepala Sekolah (Internal)</option>
                            @elseif($role == 'guru-mapel' || $role == 'guru-bk')
                                <option value="all-parents">Seluruh Orang Tua Murid</option>
                                <option value="kepala-sekolah">Kepala Sekolah (Internal)</option>
                            @endif
                        </select>
                        @error('target') <p class="text-red-500 text-xs mt-2 font-bold">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-black text-[#03045E] mb-2 uppercase tracking-widest">Isi Pengumuman</label>
                        <textarea name="konten" rows="6" class="w-full rounded-2xl border-gray-100 bg-gray-50 p-4 focus:ring-2 focus:ring-[#03045E] focus:bg-white transition-all font-semibold placeholder-gray-300" placeholder="Tulis rincian pengumuman secara mendetail di sini..." required>{{ old('konten') }}</textarea>
                        @error('konten') <p class="text-red-500 text-xs mt-2 font-bold">{{ $message }}</p> @enderror
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full py-5 bg-[#03045E] text-white rounded-full font-black shadow-xl hover:bg-blue-900 transition-all uppercase tracking-[0.2em] text-sm">
                            KIRIM SEKARANG
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>