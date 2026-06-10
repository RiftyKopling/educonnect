<x-app-layout>
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-black text-[#03045E] tracking-tight">Data Siswa</h2>
            <p class="text-gray-500">Total siswa terdaftar di SMP Negeri 2 Mungkid.</p>
        </div>
        <a href="{{ route('siswa.create') }}" class="px-6 py-3 bg-[#03045E] text-white rounded-full font-bold shadow-lg hover:scale-105 transition-all flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            TAMBAH SISWA
        </a>
    </div>

    @if(session('success'))
    <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded-r-xl shadow-sm flex items-center justify-between">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                <span class="font-bold">{{ session('success') }}</span>
            </div>
            <button type="button" class="text-green-500 hover:text-green-700" onclick="this.parentElement.remove();">&times;</button>
        </div>
    @endif

    <div class="bg-white rounded-[2rem] shadow-sm overflow-hidden p-6">
        <table class="w-full border-separate border-spacing-y-3">
            <thead>
                <tr class="text-white text-xs uppercase tracking-widest">
                    <th class="bg-[#03045E] p-4 rounded-l-full text-left">NISN</th>
                    <th class="bg-[#03045E] p-4 text-left">Nama Lengkap</th>
                    <th class="bg-[#03045E] p-4 text-left">Kelas</th>
                    <th class="bg-[#03045E] p-4 text-left">Status</th>
                    <th class="bg-[#03045E] p-4 rounded-r-full text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-[#03045E] font-medium">
                @foreach($siswa as $s)
                <tr class="bg-gray-50 hover:bg-gray-100 transition-all">
                    <td class="p-4 rounded-l-2xl font-bold">{{ $s->nisn }}</td>
                    <td class="p-4">{{ $s->nama_lengkap }}</td>
                    <td class="p-4">
                        <span class="px-3 py-1 bg-blue-50 text-blue-700 rounded-lg text-sm">
                            {{ $s->kelas->nama_kelas }}
                        </span>
                    </td>
                    <td class="p-4">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-green-500"></span>
                            {{ ucfirst($s->status) }}
                        </div>
                    </td>
                    <td class="p-4 rounded-r-2xl text-center">
                        <div class="flex justify-center gap-2">
                            <a href="{{ route('siswa.edit', $s->nisn) }}" class="p-2 bg-amber-100 text-amber-600 rounded-xl">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </a>
                            <form action="{{ route('siswa.destroy', $s->nisn) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-2 bg-red-100 text-red-600 rounded-xl hover:bg-red-200 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-6">
            {{ $siswa->links() }}
        </div>
    </div>
</x-app-layout>