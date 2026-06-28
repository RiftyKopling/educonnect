<x-app-layout>
    <div class="max-w-3xl mx-auto">
        <div class="mb-8">
            <div class="flex items-center gap-2 text-sm text-gray-500 mb-2">
                <a href="{{ route('dashboard') }}" class="hover:text-[#03045E] font-medium">Dashboard</a>
                <span>›</span>
                <a href="{{ route('catatan-pelanggaran.index') }}" class="hover:text-[#03045E] font-medium">Catatan Pelanggaran</a>
                <span>›</span>
                <span class="text-[#03045E] font-bold">Detail Insiden</span>
            </div>
            <a href="{{ route('catatan-pelanggaran.index') }}" class="flex items-center gap-2 text-sm text-gray-500 hover:text-[#03045E] font-medium mb-4">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali
            </a>
            <h2 class="text-3xl font-black text-[#03045E] tracking-tight">Detail Catatan Pelanggaran</h2>
            <p class="text-gray-500 text-sm mt-1">Informasi lengkap catatan insiden siswa.</p>
        </div>

        <div class="bg-white rounded-[2rem] shadow-sm p-8 border border-gray-100">
            <div class="space-y-6">
                <div class="grid grid-cols-3 border-b border-gray-100 pb-4">
                    <div class="text-sm font-bold text-gray-500 uppercase tracking-widest">Tanggal</div>
                    <div class="col-span-2 font-bold text-[#03045E]">{{ \Carbon\Carbon::parse($catatanPelanggaran->tanggal)->translatedFormat('l, d F Y') }}</div>
                </div>

                <div class="grid grid-cols-3 border-b border-gray-100 pb-4">
                    <div class="text-sm font-bold text-gray-500 uppercase tracking-widest">Siswa</div>
                    <div class="col-span-2">
                        <div class="font-bold text-[#03045E] text-lg">{{ $catatanPelanggaran->siswa->nama_lengkap }}</div>
                        <div class="text-sm text-gray-500">NISN: {{ $catatanPelanggaran->siswa->nisn }} | Kelas: {{ $catatanPelanggaran->siswa->kelas->nama_kelas ?? '-' }}</div>
                    </div>
                </div>

                <div class="grid grid-cols-3 border-b border-gray-100 pb-4">
                    <div class="text-sm font-bold text-gray-500 uppercase tracking-widest">Pelanggaran</div>
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
                    <div class="text-sm font-bold text-gray-500 uppercase tracking-widest">Keterangan Detail</div>
                    <div class="col-span-2 text-gray-700 leading-relaxed">
                        {!! nl2br(e($catatanPelanggaran->keterangan ?? 'Tidak ada keterangan tambahan.')) !!}
                    </div>
                </div>

                <div class="grid grid-cols-3 pb-4">
                    <div class="text-sm font-bold text-gray-500 uppercase tracking-widest">Pencatat (Guru BK)</div>
                    <div class="col-span-2 font-bold text-[#03045E]">{{ $catatanPelanggaran->guruBk->name ?? '-' }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>