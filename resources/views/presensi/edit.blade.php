<x-app-layout>
    <!-- Live as if you were to die tomorrow. Learn as if you were to live forever. - Mahatma Gandhi -->
    <div class="max-w-2xl mx-auto">
        <div class="mb-8">
            <h2 class="text-3xl font-black text-[#03045E] uppercase tracking-tight">Edit Kehadiran</h2>
            <p class="text-gray-500 font-bold">Siswa: {{ $presensi->siswa->nama_lengkap }} ({{ $presensi->mapel->nama_mapel }})</p>
        </div>

        <div class="bg-white rounded-[2rem] shadow-sm p-10 border border-gray-100">
            <form action="{{ route('presensi.update', $presensi->id) }}" method="POST">
                @csrf @method('PUT')
                
                <div class="mb-8 text-center">
                    <label class="block text-xs font-black text-[#03045E] mb-4 uppercase tracking-widest">Ubah Status Kehadiran</label>
                    <div class="flex justify-center gap-4">
                        @foreach(['H' => 'Hadir', 'S' => 'Sakit', 'I' => 'Izin', 'A' => 'Alpa', 'D' => 'Dispensasi'] as $key => $label)
                        <label class="flex flex-col items-center gap-2 cursor-pointer group">
                            <input type="radio" name="status" value="{{ $key }}" {{ $presensi->status == $key ? 'checked' : '' }} class="hidden">
                            <div class="w-12 h-12 rounded-2xl flex items-center justify-center border-2 border-gray-100 group-hover:border-[#03045E] transition-all radio-box font-black text-[#03045E]">
                                {{ $key }}
                            </div>
                            <span class="text-[10px] uppercase font-bold text-gray-400 group-hover:text-[#03045E]">{{ $label }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div class="mb-8">
                    <label class="block text-xs font-black text-[#03045E] mb-2 uppercase tracking-widest">Catatan / Keterangan</label>
                    <textarea name="catatan" rows="3" class="w-full rounded-2xl border-gray-100 bg-gray-50 p-4 focus:ring-2 focus:ring-[#03045E]" placeholder="Contoh: Surat dokter terlampir...">{{ $presensi->catatan }}</textarea>
                </div>

                <button type="submit" class="w-full py-5 bg-[#03045E] text-white rounded-full font-black shadow-xl uppercase tracking-widest hover:bg-blue-900 transition-all">
                    Simpan Perubahan
                </button>
            </form>
        </div>
    </div>

    <style>
        input[type="radio"]:checked + .radio-box {
            background-color: #03045E;
            color: white;
            border-color: #03045E;
            box-shadow: 0 10px 15px -3px rgba(3, 4, 94, 0.3);
        }
    </style>
</x-app-layout>