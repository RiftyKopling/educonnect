<x-app-layout>
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-black text-[#03045E] tracking-tight uppercase">Mata Pelajaran</h2>
            <p class="text-gray-500 font-medium">Kelola daftar mata pelajaran kurikulum sekolah.</p>
        </div>
        <a href="{{ route('mapel.create') }}" class="px-8 py-3 bg-[#03045E] text-white rounded-full font-bold shadow-lg hover:scale-105 transition-all flex items-center gap-2 tracking-widest text-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            TAMBAH MAPEL
        </a>
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
                <tr class="text-white text-xs uppercase tracking-[0.2em] font-black">
                    <th class="bg-[#03045E] p-5 rounded-l-full text-left pl-8">Kode Mapel</th>
                    <th class="bg-[#03045E] p-5 text-left">Nama Mata Pelajaran</th>
                    <th class="bg-[#03045E] p-5 rounded-r-full text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-[#03045E] font-bold">
                @forelse($data_mapel as $mapel)
                <tr class="bg-gray-50 hover:bg-white hover:shadow-md transition-all group">
                    <td class="p-5 rounded-l-2xl pl-8">
                        <span class="bg-[#03045E]/10 px-4 py-2 rounded-xl text-sm">{{ $mapel->kode_mapel }}</span>
                    </td>
                    <td class="p-5 text-lg">{{ $mapel->nama_mapel }}</td>
                    <td class="p-5 rounded-r-2xl text-center">
                        <div class="flex justify-center gap-3">
                            <a href="{{ route('mapel.edit', $mapel->id) }}" class="p-3 bg-amber-100 text-amber-600 rounded-2xl hover:bg-amber-500 hover:text-white transition-all shadow-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </a>
                            <form action="{{ route('mapel.destroy', $mapel->id) }}" method="POST" onsubmit="return confirm('Hapus mata pelajaran ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-3 bg-red-100 text-red-600 rounded-2xl hover:bg-red-500 hover:text-white transition-all shadow-sm">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="p-10 text-center text-gray-400 font-medium italic">Belum ada data mata pelajaran.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-6">
            {{ $data_mapel->links() }}
        </div>
    </div>
</x-app-layout>