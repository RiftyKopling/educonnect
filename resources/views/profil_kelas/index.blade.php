<x-app-layout>
    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-4">
        <a href="{{ route('dashboard') }}" class="hover:text-[#03045E] font-medium flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Dashboard
        </a>
        <span>›</span>
        <span class="text-[#03045E] font-bold">Profil Kelas</span>
    </div>

    <!-- Header -->
    <div class="mb-6 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
        <div> 
            <h2 class="text-3xl font-black text-[#03045E] tracking-tight">Profil Kelas {{ $kelas->nama_kelas }}</h2>
            <p class="text-gray-500 text-sm mt-1">Tingkat: {{ $kelas->tingkat }} | Tahun Ajaran: {{ $kelas->tahun_ajaran }}</p>
        </div>

        <div class="px-6 py-3 bg-[#03045E] text-white rounded-full font-bold shadow-lg flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            TOTAL SISWA: {{ $siswa->total() }}
        </div>
    </div>

    <!-- Search Form -->
    <form method="GET" action="{{ route('profil-kelas.index') }}" class="flex gap-3 mb-6">
        <div class="flex-1 relative">
            <input type="text" 
                name="search" 
                value="{{ request('search') }}"
                placeholder="Cari nama atau NISN..."
                class="w-full rounded-full border-gray-200 pl-12 pr-4 py-3 focus:ring-[#03045E] focus:border-[#03045E] shadow-sm">
            <svg class="w-5 h-5 absolute left-4 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        </div>

        <button type="submit" class="px-6 py-3 bg-[#03045E] text-white rounded-full font-bold shadow-lg hover:scale-105 transition-all">
            Cari
        </button>

        @if(request('search'))
            <a href="{{ route('profil-kelas.index') }}" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-full font-bold shadow-sm hover:scale-105 transition-all">
                Reset
            </a>
        @endif
    </form>

    <!-- Data Table -->
    <div class="bg-white rounded-[2rem] shadow-sm overflow-hidden p-6">
        <div class="overflow-x-auto">
            <table class="w-full border-separate border-spacing-y-3">
                <thead>
                    <tr class="text-white text-sm uppercase tracking-widest">
                        <th class="bg-[#03045E] p-4 rounded-l-full text-left">NISN</th>
                        <th class="bg-[#03045E] p-4 text-left">Nama Lengkap</th>
                        <th class="bg-[#03045E] p-4 text-left">Jenis Kelamin</th>
                        <th class="bg-[#03045E] p-4 rounded-r-full text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="text-[#03045E] font-medium">
                    @forelse($siswa as $s)
                    <tr class="bg-gray-50 hover:bg-gray-100 transition-all">
                        <td class="p-4 rounded-l-2xl font-bold">{{ $s->nisn }}</td>
                        <td class="p-4">{{ $s->nama_lengkap }}</td>
                        <td class="p-4">
                            @if($s->jenis_kelamin === 'L')
                                Laki-Laki
                            @elseif($s->jenis_kelamin === 'P')
                                Perempuan
                            @else
                                -
                            @endif
                        </td>
                        <td class="p-4 rounded-r-2xl text-center">
                            <span class="px-3 py-1 {{ $s->status == 'aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} rounded-full text-xs font-bold uppercase">
                                {{ $s->status ?? 'N/A' }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="p-8 text-center text-gray-400">
                            <div class="flex flex-col items-center gap-2">
                                <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                <span class="font-bold text-gray-400">Tidak ada siswa ditemukan di kelas ini</span>
                                @if(request('search'))
                                    <span class="text-sm text-gray-400">Coba ubah kata kunci pencarian</span>
                                    <a href="{{ route('profil-kelas.index') }}" class="mt-2 px-4 py-2 bg-gray-100 text-gray-600 rounded-full text-sm font-bold hover:bg-gray-200 transition-all">Reset Pencarian</a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-6">
            {{ $siswa->links() }}
        </div>
    </div>
</x-app-layout>
