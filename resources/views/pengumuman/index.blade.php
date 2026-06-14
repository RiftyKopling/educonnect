<x-app-layout>
    <!-- People find pleasure in different ways. I find it in keeping my mind clear. - Marcus Aurelius -->
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-black text-[#03045E] tracking-tight uppercase">Manajemen Pengumuman</h2>
            <p class="text-gray-500 font-medium">Daftar pengumuman yang telah Anda kirimkan.</p>
        </div>
        @if(auth()->user()->role?->slug !== 'orang-tua')
        <a href="{{ route('pengumuman.create') }}" class="px-8 py-3 bg-[#03045E] text-white rounded-full font-bold shadow-lg hover:scale-105 transition-all tracking-widest text-sm">
            BUAT PENGUMUMAN
        </a>
        @endif
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-500 text-white rounded-2xl shadow-md flex items-center gap-3 font-bold italic">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-[2rem] shadow-sm p-6 border border-gray-100">
        <table class="w-full border-separate border-spacing-y-3">
            <thead>
                <tr class="text-white text-xs uppercase tracking-widest">
                    <th class="bg-[#03045E] p-5 rounded-l-full text-left">Judul</th>
                    <th class="bg-[#03045E] p-5 text-left">Target</th>
                    <th class="bg-[#03045E] p-5 text-left">Tanggal</th>
                    <th class="bg-[#03045E] p-5 rounded-r-full text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-[#03045E] font-bold">
                @foreach($data_pengumuman as $p)
                <tr class="bg-gray-50 hover:bg-white transition-all">
                    <td class="p-5 rounded-l-2xl">{{ $p->judul }}</td>
                    <td class="p-5">
                        <span class="px-4 py-1 bg-blue-100 rounded-full text-xs uppercase">{{ str_replace('-', ' ', $p->target_type) }}</span>
                    </td>
                    <td class="p-5 italic text-sm">{{ $p->created_at->format('d M Y') }}</td>
                    <td class="p-5 rounded-r-2xl text-center">
                        <form action="{{ route('pengumuman.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Hapus pengumuman ini?')">
                            @csrf 
                            @method('DELETE')
                            <button type="submit" class="p-2 bg-red-100 text-red-600 rounded-xl hover:bg-red-500 hover:text-white transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-6">
            {{ $data_pengumuman->links() }}
        </div>
    </div>
</x-app-layout>

