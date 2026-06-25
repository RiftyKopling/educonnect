<x-app-layout>
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-4">
        <a href="{{ route('dashboard') }}" class="hover:text-[#03045E] font-medium flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Dashboard
        </a>
        <span>›</span>
        <span class="text-[#03045E] font-bold">Master Pelanggaran</span>
    </div>

    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-black text-[#03045E] tracking-tight uppercase">Master Pelanggaran</h2>
            <p class="text-gray-500">Kelola jenis-jenis pelanggaran beserta kategorinya.</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('pelanggaran.create') }}" class="px-6 py-3 bg-[#03045E] text-white rounded-full font-bold shadow-lg hover:scale-105 transition-all flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                TAMBAH PELANGGARAN
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-500 text-white rounded-2xl shadow-md font-bold flex items-center gap-2">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-[2rem] shadow-sm p-6 border border-gray-100 overflow-x-auto">
        <table class="w-full border-separate border-spacing-y-3 min-w-[800px]">
            <thead>
                <tr class="text-white text-xs uppercase tracking-[0.1em] font-black">
                    <th class="bg-[#03045E] p-4 rounded-l-full text-left pl-6">Jenis Pelanggaran</th>
                    <th class="bg-[#03045E] p-4 text-left">Kategori</th>
                    <th class="bg-[#03045E] p-4 text-left">Deskripsi</th>
                    <th class="bg-[#03045E] p-4 rounded-r-full text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-[#03045E] font-medium">
                @forelse($pelanggarans as $p)
                <tr class="bg-gray-50 hover:bg-white hover:shadow-md transition-all group">
                    <td class="p-4 rounded-l-2xl pl-6 font-bold text-lg">
                        {{ $p->nama_pelanggaran }}
                    </td>
                    <td class="p-4">
                        <span class="px-3 py-1 rounded-full font-bold text-xs 
                            {{ $p->kategori == 'Ringan' ? 'bg-emerald-100 text-emerald-600' : 
                              ($p->kategori == 'Sedang' ? 'bg-amber-100 text-amber-600' : 'bg-red-100 text-red-600') }}">
                            {{ $p->kategori }}
                        </span>
                    </td>
                    <td class="p-4 text-sm text-gray-600">
                        {{ $p->deskripsi ?? '-' }}
                    </td>
                    <td class="p-4 rounded-r-2xl text-center">
                        <div class="flex justify-center gap-2">
                            <a href="{{ route('pelanggaran.edit', $p->id) }}" class="px-3 py-2 bg-amber-100 text-amber-600 rounded-xl hover:bg-amber-500 hover:text-white transition-all font-bold text-xs">Edit</a>
                            <form action="{{ route('pelanggaran.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Hapus data ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="px-3 py-2 bg-red-100 text-red-600 rounded-xl hover:bg-red-500 hover:text-white transition-all font-bold text-xs">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="p-10 text-center text-gray-400 font-medium italic">Belum ada data pelanggaran.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-app-layout>
