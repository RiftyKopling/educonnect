<x-app-layout>
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-4">
        <a href="{{ route('konseling.index') }}" class="hover:text-[#03045E] font-medium flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Jadwal Konseling
        </a>
        <span>›</span>
        <span class="text-[#03045E] font-bold">Detail Sesi</span>
    </div>

    <div class="mb-6 flex justify-between items-center">
        <h2 class="text-3xl font-black text-[#03045E] tracking-tight uppercase">Detail Sesi Konseling</h2>
        @if(auth()->user()->hasRole('guru-bk') || auth()->user()->hasRole('admin-sekolah'))
            <a href="{{ route('konseling.edit', $konseling->id) }}" class="px-6 py-3 bg-amber-500 text-white rounded-full font-bold shadow-lg hover:scale-105 transition-all">EDIT SESI</a>
        @endif
    </div>

    <div class="bg-white rounded-[2rem] shadow-sm p-8 border border-gray-100 max-w-3xl">
        <div class="space-y-6">
            
            <div class="flex items-center gap-4 border-b border-gray-100 pb-6">
                <div class="flex-1">
                    <div class="text-sm font-bold text-gray-500 uppercase mb-1">Status Sesi</div>
                    <span class="px-4 py-2 rounded-full font-black text-sm uppercase
                        {{ $konseling->status == 'Selesai' ? 'bg-emerald-100 text-emerald-600' : 
                          ($konseling->status == 'Terjadwal' ? 'bg-blue-100 text-blue-600' : 'bg-red-100 text-red-600') }}">
                        {{ $konseling->status }}
                    </span>
                </div>
                <div class="flex-1 text-right">
                    <div class="text-sm font-bold text-gray-500 uppercase mb-1">Jadwal Sesi</div>
                    <div class="font-bold text-[#03045E] text-lg">{{ \Carbon\Carbon::parse($konseling->tanggal)->translatedFormat('l, d F Y') }}</div>
                    <div class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($konseling->tanggal)->format('H:i') }} WIB</div>
                </div>
            </div>

            <div class="grid grid-cols-3 border-b border-gray-100 pb-4">
                <div class="text-sm font-bold text-gray-500 uppercase">Siswa</div>
                <div class="col-span-2">
                    <div class="font-bold text-[#03045E] text-lg">{{ $konseling->siswa->nama_lengkap }}</div>
                    <div class="text-sm text-gray-500">NISN: {{ $konseling->siswa->nisn }} | Kelas: {{ $konseling->siswa->kelas->nama_kelas ?? '-' }}</div>
                </div>
            </div>

            <div class="grid grid-cols-3 border-b border-gray-100 pb-4">
                <div class="text-sm font-bold text-gray-500 uppercase">Informasi Layanan</div>
                <div class="col-span-2">
                    <div class="font-bold text-[#03045E]">{{ $konseling->jenis_layanan }}</div>
                    <div class="text-sm text-gray-600 mt-1"><span class="font-bold text-gray-500">Topik:</span> {{ $konseling->topik }}</div>
                </div>
            </div>

            <div class="grid grid-cols-3 border-b border-gray-100 pb-4">
                <div class="text-sm font-bold text-gray-500 uppercase">Deskripsi Kasus</div>
                <div class="col-span-2 text-gray-700 leading-relaxed {{ $konseling->deskripsi_kasus === '*** Dirahasiakan demi privasi siswa ***' ? 'italic font-bold text-gray-400' : '' }}">
                    {!! nl2br(e($konseling->deskripsi_kasus ?? '-')) !!}
                </div>
            </div>

            <div class="grid grid-cols-3 border-b border-gray-100 pb-4">
                <div class="text-sm font-bold text-gray-500 uppercase">Tindak Lanjut</div>
                <div class="col-span-2 text-gray-700 leading-relaxed {{ $konseling->tindak_lanjut === '*** Dirahasiakan demi privasi siswa ***' ? 'italic font-bold text-gray-400' : '' }}">
                    {!! nl2br(e($konseling->tindak_lanjut ?? '-')) !!}
                </div>
            </div>

            <div class="grid grid-cols-3 pb-4">
                <div class="text-sm font-bold text-gray-500 uppercase">Guru Pembimbing</div>
                <div class="col-span-2 font-bold text-[#03045E]">
                    {{ $konseling->guruBk->name ?? '-' }}
                </div>
            </div>

            <div class="pt-6">
                <a href="{{ route('konseling.index') }}" class="px-8 py-3 bg-gray-100 text-gray-500 rounded-full font-bold hover:bg-gray-200 transition-all inline-block">KEMBALI</a>
            </div>
        </div>
    </div>
</x-app-layout>
