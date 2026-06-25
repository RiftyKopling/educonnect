<x-app-layout>
    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-2">
        <a href="{{ route('dashboard') }}" class="hover:text-[#03045E] font-medium">
            Dashboard
        </a>
        <span>›</span>
        <a href="{{ route('kelas.index') }}" class="hover:text-[#03045E] font-medium">Manajemen Kelas</a>
        <span>›</span>
        <span class="text-[#03045E] font-bold">Detail Kelas</span>
    </div>

    <!-- Tombol Kembali -->
    <a href="{{ route('kelas.index') }}" class="flex items-center gap-2 text-sm text-gray-500 hover:text-[#03045E] font-medium mb-4">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Kembali
    </a>

    <!-- Header -->
    <div class="mb-6 flex justify-between items-center flex-wrap gap-3">
        <div>
            <h2 class="text-3xl font-black text-[#03045E] tracking-tight">Kelas {{ $kelas->nama_kelas }}</h2>
            <p class="text-gray-500 text-sm mt-1">Detail informasi dan daftar siswa kelas {{ $kelas->nama_kelas }}</p>
        </div>

        <a href="{{ route('kelas.edit', $kelas->id) }}" class="px-6 py-3 bg-[#03045E] text-white rounded-full font-bold shadow-lg hover:scale-105 transition-all flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            EDIT KELAS
        </a>
    </div>

    <!-- Statistik & Info Kelas -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <!-- Info Kelas -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
            <h4 class="text-sm font-bold text-[#03045E] uppercase tracking-widest mb-3">Informasi Kelas</h4>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between py-1 border-b border-gray-50">
                    <span class="text-gray-500 font-medium">Nama Kelas</span>
                    <span class="font-bold text-[#03045E]">{{ $kelas->nama_kelas }}</span>
                </div>
                <div class="flex justify-between py-1 border-b border-gray-50">
                    <span class="text-gray-500 font-medium">Tingkat</span>
                    <span class="font-bold text-[#03045E]">Tingkat {{ $kelas->tingkat }}</span>
                </div>
                <div class="flex justify-between py-1 border-b border-gray-50">
                    <span class="text-gray-500 font-medium">Tahun Ajaran</span>
                    <span class="font-bold text-[#03045E]">{{ $kelas->tahun_ajaran }}</span>
                </div>
                <div class="flex justify-between py-1">
                    <span class="text-gray-500 font-medium">Wali Kelas</span>
                    <span class="font-bold text-[#03045E]">{{ $kelas->waliKelas->name ?? 'Belum ditentukan' }}</span>
                </div>
            </div>
        </div>

        <!-- Statistik Siswa -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
            <h4 class="text-sm font-bold text-[#03045E] uppercase tracking-widest mb-3">Statistik Siswa</h4>
            <div class="grid grid-cols-2 gap-3">
                <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-black text-[#03045E]">{{ $kelas->siswa->count() }}</p>
                            <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Total Siswa</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-black text-[#03045E]">{{ $kelas->siswa->where('jenis_kelamin', 'L')->count() }}</p>
                            <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Siswa Laki-laki</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-pink-100 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-black text-pink-600">{{ $kelas->siswa->where('jenis_kelamin', 'P')->count() }}</p>
                            <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Siswa Perempuan</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-black text-green-600">{{ $kelas->siswa->where('status', 'aktif')->count() }}</p>
                            <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Siswa Aktif</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Daftar Siswa -->
    <div class="bg-white rounded-[2rem] shadow-sm overflow-hidden p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-black text-[#03045E]">Daftar Siswa</h3>
        </div>

        @if($kelas->siswa->count() > 0)
            <table class="w-full border-separate border-spacing-y-3">
                <thead>
                    <tr class="text-white text-sm uppercase tracking-widest">
                        <th class="bg-[#03045E] p-4 rounded-l-full text-left w-16">NO</th>
                        <th class="bg-[#03045E] p-4 text-left">NISN</th>
                        <th class="bg-[#03045E] p-4 text-left">Nama Lengkap</th>
                        <th class="bg-[#03045E] p-4 text-left">Jenis Kelamin</th>
                        <th class="bg-[#03045E] p-4 text-left">Status</th>
                        <th class="bg-[#03045E] p-4 rounded-r-full text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-[#03045E] font-medium">
                    @foreach($kelas->siswa as $index => $siswa)
                    <tr class="bg-gray-50 hover:bg-gray-100 transition-all">
                        <td class="p-4 rounded-l-2xl text-center font-bold text-gray-400">{{ $index + 1 }}</td>
                        <td class="p-4 font-bold">{{ $siswa->nisn }}</td>
                        <td class="p-4">{{ $siswa->nama_lengkap }}</td>
                        <td class="p-4">
                            <span class="px-3 py-1 {{ $siswa->jenis_kelamin == 'L' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800' }} rounded-full text-xs font-bold uppercase">
                                {{ $siswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                            </span>
                        </td>
                        <td class="p-4">
                            <span class="px-3 py-1 {{ $siswa->status == 'aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} rounded-full text-xs font-bold uppercase">
                                {{ $siswa->status }}
                            </span>
                        </td>
                        <td class="p-4 rounded-r-2xl text-center">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('siswa.edit', $siswa->nisn) }}" class="p-2 bg-amber-100 text-amber-600 rounded-xl hover:bg-amber-200 transition-colors" title="Edit Siswa">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="p-8 text-center text-gray-400">
                <div class="flex flex-col items-center gap-2">
                    <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span class="font-bold text-gray-400">Belum ada siswa di kelas ini</span>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>