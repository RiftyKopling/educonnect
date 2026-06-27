<x-app-layout>
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-4">
        <a href="{{ route('catatan-pelanggaran.index') }}" class="hover:text-[#03045E] font-medium flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Catatan Pelanggaran
        </a>
        <span>›</span>
        <span class="text-[#03045E] font-bold">Detail Insiden</span>
    </div>

    <div class="mb-6">
        <h2 class="text-3xl font-black text-[#03045E] tracking-tight uppercase">Detail Catatan Pelanggaran</h2>
    </div>

    <div class="bg-white rounded-[2rem] shadow-sm p-8 border border-gray-100 max-w-3xl">
        <div class="space-y-6">
            <div class="grid grid-cols-3 border-b border-gray-100 pb-4">
                <div class="text-sm font-bold text-gray-500 uppercase">Tanggal</div>
                <div class="col-span-2 font-bold text-[#03045E]">{{ \Carbon\Carbon::parse($catatanPelanggaran->tanggal)->translatedFormat('l, d F Y') }}</div>
            </div>

            <div class="grid grid-cols-3 border-b border-gray-100 pb-4">
                <div class="text-sm font-bold text-gray-500 uppercase">Siswa</div>
                <div class="col-span-2">
                    <div class="font-bold text-[#03045E] text-lg">{{ $catatanPelanggaran->siswa->nama_lengkap }}</div>
                    <div class="text-sm text-gray-500">NISN: {{ $catatanPelanggaran->siswa->nisn }} | Kelas: {{ $catatanPelanggaran->siswa->kelas->nama_kelas ?? '-' }}</div>
                </div>
            </div>

            <div class="grid grid-cols-3 border-b border-gray-100 pb-4">
                <div class="text-sm font-bold text-gray-500 uppercase">Pelanggaran</div>
                <div class="col-span-2">
                    <div class="font-bold text-[#03045E] text-lg">{{ $catatanPelanggaran->pelanggaran->nama_pelanggaran }}</div>
                    <span class="px-3 py-1 mt-2 inline-block rounded-full font-bold text-xs uppercase
                        {{ ($catatanPelanggaran->pelanggaran->kategori ?? '') == 'Ringan' ? 'bg-emerald-100 text-emerald-600' : 
                          (($catatanPelanggaran->pelanggaran->kategori ?? '') == 'Sedang' ? 'bg-amber-100 text-amber-600' : 'bg-red-100 text-red-600') }}">
                        {{ $catatanPelanggaran->pelanggaran->kategori }}
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-3 border-b border-gray-100 pb-4">
                <div class="text-sm font-bold text-gray-500 uppercase">Keterangan Detail</div>
                <div class="col-span-2 text-gray-700 leading-relaxed">
                    {!! nl2br(e($catatanPelanggaran->keterangan ?? 'Tidak ada keterangan tambahan.')) !!}
                </div>
            </div>

            <div class="grid grid-cols-3 pb-4">
                <div class="text-sm font-bold text-gray-500 uppercase">Pencatat (Guru BK)</div>
                <div class="col-span-2 font-bold text-[#03045E]">
                    {{ $catatanPelanggaran->guruBk->name ?? '-' }}
                </div>
            </div>

            <div class="pt-6">
                <a href="{{ route('catatan-pelanggaran.index') }}" class="px-8 py-3 bg-gray-100 text-gray-500 rounded-full font-bold hover:bg-gray-200 transition-all inline-block">KEMBALI</a>
            </div>
        </div>
    </div>
</x-app-layout>
