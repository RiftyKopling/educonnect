<x-app-layout>
    <div class="relative overflow-hidden bg-gradient-to-br from-[#00B4D8] via-[#0077B6] to-[#03045E] rounded-3xl p-10 text-white shadow-2xl mb-8">
        <div class="relative z-10">
            <h2 class="text-4xl font-extrabold mb-2">Selamat Datang, {{ explode(' ', Auth::user()->name)[0] }}</h2>
            <p class="text-white/80 font-light tracking-wide italic">Pantau dan kelola aktivitas sekolah dalam satu platform terpadu.</p>
        </div>
        <div class="absolute -right-20 -top-20 w-80 h-80 bg-white/10 rounded-full blur-3xl"></div>
    </div>

    <div class="grid grid-cols-2 gap-6 mb-10" style="grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));">
        @php
            $userRoleSlug = auth()->user()->role?->slug;
        
            // Konfigurasi Quick Cards yang sudah diperbarui
            $cards = [
                'admin-sekolah' => ['Manajemen Pengguna', 'Manajemen Siswa', 'Manajemen Kelas', 'Manajemen Mapel'],
                'guru-mapel'    => ['Presensi Siswa', 'Input Nilai', 'Materi Ajar', 'Pesan', 'Pengumuman'],
                'guru-bk'       => ['Catatan Konseling', 'Laporan Perkembangan'],
                'wali-kelas'    => ['Rekap Nilai', 'Rekap Presensi', 'Catatan BK Siswa', 'Profil Kelas', 'Pengumuman'],
                'kepala-sekolah'=> ['Laporan Akademik', 'Buat Pengumuman'],
                'orang-tua'     => ['Nilai Anak', 'Presensi Anak', 'Pengumuman'],
            ];
            
            $currentCards = $cards[$userRoleSlug] ?? ['Data Akademik', 'Presensi', 'Konseling', 'Pengumuman'];
        @endphp

        @foreach($currentCards as $card)
        <div class="bg-white p-6 rounded-[2.5rem] shadow-soft border border-gray-50 flex flex-col items-center justify-center gap-3 transition-transform hover:-translate-y-1 hover:shadow-xl cursor-pointer">
            <div class="w-12 h-12 bg-gray-100 rounded-2xl flex items-center justify-center text-[#03045E]">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
            </div>
            <span class="text-[#03045E] font-bold text-sm tracking-tight text-center">{{ $card }}</span>
        </div>
        @endforeach
    </div>

    <div class="bg-white rounded-[2rem] shadow-soft p-8 border border-gray-100">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-black text-[#03045E] tracking-tight">Daftar Pengumuman Terbaru</h3>
            <a href="{{ route('pengumuman.masuk') }}" class="text-sm font-bold text-[#03045E] hover:underline flex items-center gap-1">
                Lihat Semua
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
        </div>

        <div class="overflow-hidden">
            <table class="w-full border-separate border-spacing-y-3">
                <thead>
                    <tr class="text-white">
                        <th class="bg-[#03045E] p-4 rounded-l-full text-left font-bold tracking-widest text-xs uppercase pl-6">Judul</th>
                        <th class="bg-[#03045E] p-4 text-left font-bold tracking-widest text-xs uppercase">Pengirim</th>
                        <th class="bg-[#03045E] p-4 text-center font-bold tracking-widest text-xs uppercase">Target</th>
                        <th class="bg-[#03045E] p-4 rounded-r-full text-right font-bold tracking-widest text-xs uppercase pr-6">Waktu</th>
                    </tr>
                </thead>
                <tbody class="text-[#03045E] font-medium">
                    @forelse($announcements as $pengumuman)
                    <tr class="bg-gray-50 hover:bg-white hover:shadow-md transition-all">
                        <td class="p-4 rounded-l-2xl font-bold pl-6">{{ $pengumuman->judul }}</td>
                        <td class="p-4 text-sm">{{ $pengumuman->user?->name }}</td>
                        <td class="p-4 text-center">
                            <span class="px-3 py-1 bg-blue-100 text-[#03045E] rounded-full text-[10px] font-black uppercase tracking-wider">
                                {{ str_replace('-', ' ', $pengumuman->target_type) }}
                            </span>
                        </td>
                        <td class="p-4 rounded-r-2xl text-right text-sm italic text-gray-500 pr-6">
                            {{ $pengumuman->created_at->diffForHumans() }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="p-8 text-center text-gray-400 font-medium italic rounded-2xl bg-gray-50">
                            Belum ada pengumuman terbaru untuk Anda saat ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>