<x-app-layout>
    <!-- Nothing in life is to be feared, it is only to be understood. 
     Now is the time to understand more, so that we may fear less. - Maria Skłodowska-Curie -->
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-black text-[#03045E] tracking-tight uppercase">Papan Pengumuman</h2>
            <p class="text-gray-500 font-medium">Seluruh informasi dan pengumuman masuk untuk Anda.</p>
        </div>
        <a href="{{ route('dashboard') }}" class="text-[#03045E] font-bold flex items-center gap-2 hover:underline uppercase tracking-widest text-xs">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Dasbor
        </a>
    </div>

    <div class="space-y-6">
        @forelse($data_pengumuman as $pengumuman)
        <div class="bg-white rounded-[2rem] shadow-sm p-8 border border-gray-100 hover:shadow-md transition-all">
            <div class="flex justify-between items-start mb-4">
                <h3 class="text-xl font-black text-[#03045E]">{{ $pengumuman->judul }}</h3>
                <span class="px-4 py-1.5 bg-blue-50 text-[#03045E] rounded-full text-xs font-black uppercase tracking-widest">
                    {{ $pengumuman->created_at->format('d M Y') }}
                </span>
            </div>
            
            <p class="text-gray-600 font-medium leading-relaxed mb-6 whitespace-pre-line">
                {{ $pengumuman->konten }}
            </p>
            
            <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                <div class="w-8 h-8 rounded-full bg-[#03045E] flex items-center justify-center text-white text-xs font-bold">
                    {{ substr($pengumuman->user?->name, 0, 1) }}
                </div>
                <div>
                    <p class="text-xs font-bold text-[#03045E]">Dikirim oleh: {{ $pengumuman->user?->name }}</p>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Target: {{ str_replace('-', ' ', $pengumuman->target_type) }}</p>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-[2rem] shadow-sm p-12 text-center border border-gray-100">
            <p class="text-gray-400 font-medium italic">Belum ada pengumuman untuk Anda.</p>
        </div>
        @endforelse

        <div class="mt-8">
            {{ $data_pengumuman->links() }}
        </div>
    </div>
</x-app-layout>
