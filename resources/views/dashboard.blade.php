<x-app-layout>
    @php
    $banner = asset('images/banner_dashboard.png');
    @endphp

    <div id="banner-dashboard"
        class="relative overflow-hidden rounded-3xl p-10 text-white shadow-2xl mb-8 bg-cover bg-center"
        style="background-image: url('{{ $banner }}');">
        <div class="relative z-10 flex justify-between items-center">
            <div>
                <h2 class="text-4xl font-extrabold mb-2">
                    Selamat Datang, {{ collect(explode(' ', Auth::user()->name))->take(2)->implode(' ')  }}
                </h2>
                <p class="text-white/80 font-light tracking-wide italic">
                    Pantau dan Kelola Aktivitas Sekolah dalam Satu Platform Terpadu.
                </p>
            </div>
            <div class="text-right hidden lg:block">
                <div id="jam-realtime" class="text-5xl font-black tracking-tight"></div>
                <div id="tanggal-realtime" class="text-white/80 text-sm mt-1"></div>
            </div>
        </div>
    </div>

    <img src="{{ $banner }}"
        style="display:none;"
        onerror="
            var el = document.getElementById('banner-dashboard');
            el.style.backgroundImage = 'none';
            el.style.background = 'linear-gradient(to bottom right, #ff5400, #ff8500, #ff9e00)';
        ">

    <div class="grid grid-cols-2 gap-6 mb-10" style=" grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));">
        @php
        $userRoleSlug = auth()->user()->role?->slug;

        // Konfigurasi Quick Cards yang sudah diperbarui
        $cards = [
        'admin-sekolah' => [
            ['name' => 'Manajemen Pengguna', 'icon' => 'users', 'route' => 'users.index'],
            ['name' => 'Manajemen Siswa', 'icon' => 'user-group', 'route' => 'siswa.index'],
            ['name' => 'Manajemen Kelas', 'icon' => 'academic-cap', 'route' => 'kelas.index'],
            ['name' => 'Manajemen Mata Pelajaran', 'icon' => 'book-open', 'route' => 'mapel.index'],
            ['name' => 'Manajemen Pengumuman', 'icon' => 'megaphone', 'route' => 'pengumuman.index'],
            ['name' => 'Papan Pengumuman', 'icon' => 'bell', 'route' => 'pengumuman.masuk'],
            ['name' => 'Master Pelanggaran', 'icon' => 'exclamation-triangle', 'route' => 'pelanggaran.index'],
            ],

            'guru-mapel' => [
            ['name' => 'Manajemen Presensi Siswa', 'icon' => 'clipboard-document-check', 'route' => 'presensi.index'],
            ['name' => 'Input Nilai Akademik', 'icon' => 'pencil-square', 'route' => 'nilai.index'],
            ['name' => 'Bank Materi Ajar', 'icon' => 'document-text', 'route' => 'materi-ajar.index'],
            ['name' => 'Manajemen Pengumuman', 'icon' => 'megaphone', 'route' => 'pengumuman.index'],
            ['name' => 'Papan Pengumuman', 'icon' => 'bell', 'route' => 'pengumuman.masuk'],
            ],

            'guru-bk' => [
            ['name' => 'Catatan Pelanggaran', 'icon' => 'folder-open', 'route' => 'catatan-pelanggaran.index'],
            ['name' => 'Jadwal Konseling', 'icon' => 'chat-bubble-left-right', 'route' => 'konseling.index'],
            ['name' => 'Manajemen Pengumuman', 'icon' => 'megaphone', 'route' => 'pengumuman.index'],
            ['name' => 'Papan Pengumuman', 'icon' => 'bell', 'route' => 'pengumuman.masuk'],
            ],

            'wali-kelas' => [
            ['name' => 'Rekap Nilai', 'icon' => 'document-chart-bar', 'route' => 'nilai.index'],
            ['name' => 'Rekap Presensi', 'icon' => 'clipboard-document-list', 'route' => 'presensi.index'],
            ['name' => 'Catatan Pelanggaran', 'icon' => 'folder-open', 'route' => 'catatan-pelanggaran.index'],
            ['name' => 'Jadwal Konseling', 'icon' => 'chat-bubble-left-right', 'route' => 'konseling.index'],
            ['name' => 'Profil Kelas', 'icon' => 'user-group', 'route' => null],
            ['name' => 'Manajemen Pengumuman', 'icon' => 'megaphone', 'route' => 'pengumuman.index'],
            ['name' => 'Papan Pengumuman', 'icon' => 'bell', 'route' => 'pengumuman.masuk'],
            ],

            'kepala-sekolah' => [
            ['name' => 'Laporan Akademik', 'icon' => 'chart-bar', 'route' => null],
            ['name' => 'Monitoring Sistem', 'icon' => 'shield-check', 'route' => null], 
            ['name' => 'Manajemen Pengumuman', 'icon' => 'megaphone', 'route' => 'pengumuman.index'],
            ['name' => 'Papan Pengumuman', 'icon' => 'bell', 'route' => 'pengumuman.masuk'],
            ],

            'orang-tua' => [
            ['name' => 'Nilai Anak', 'icon' => 'academic-cap', 'route' => 'nilai.index'],
            ['name' => 'Presensi Anak', 'icon' => 'check-badge', 'route' => 'presensi.index'],
            ['name' => 'Catatan BK Anak', 'icon' => 'folder-open', 'route' => 'catatan-pelanggaran.index'],
            ['name' => 'Jadwal Konseling', 'icon' => 'chat-bubble-left-right', 'route' => 'konseling.index'],
            ['name' => 'Papan Pengumuman', 'icon' => 'bell', 'route' => 'pengumuman.masuk'],
            ],
        ];

        // Cari kartu berdasarkan slug, jika tidak ada gunakan default
        $currentCards = $cards[$userRoleSlug] ?? [
        ['name' => 'Data Akademik', 'icon' => 'academic-cap', 'route' => null],
        ['name' => 'Presensi', 'icon' => 'clipboard-document-check', 'route' => null],
        ['name' => 'Konseling', 'icon' => 'chat-bubble-left-right', 'route' => null],
        ['name' => 'Pengumuman', 'icon' => 'bell', 'route' => null],
        ];
        @endphp

        @foreach($currentCards as $card)
        @php
        $url = $card['route'] ? route($card['route']) : '#';
        @endphp
        <a href="{{ $url }}"
            class="bg-white p-6 rounded-[2.5rem] shadow-soft border border-gray-50 flex flex-col items-center justify-center gap-3 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl group cursor-pointer"
            onmouseover="this.style.background='linear-gradient(to bottom right, #0077B6, #03045E)'; this.querySelector('span').style.color='white'; this.querySelector('.icon-wrap').style.backgroundColor='rgba(255,255,255,0.2)'; this.querySelector('.icon-wrap').style.color='white';"
            onmouseout="this.style.background='white'; this.querySelector('span').style.color=''; this.querySelector('.icon-wrap').style.backgroundColor=''; this.querySelector('.icon-wrap').style.color='';">
            <div class="icon-wrap w-12 h-12 bg-gray-100 rounded-2xl flex items-center justify-center text-navy transition-all duration-300">
                @svg('heroicon-o-' . $card['icon'], 'w-6 h-6')
            </div>
            <span class="text-navy font-bold text-sm tracking-tight transition-all duration-300 text-center">{{ $card['name'] }}</span>
        </a>
        @endforeach
    </div>

    <div class="bg-white rounded-[2rem] shadow-soft p-8 border border-gray-100">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-black text-[#03045E] tracking-tight">Daftar Pengumuman Terbaru</h3>
            <a href="{{ route('pengumuman.masuk') }}" class="text-sm font-bold text-[#03045E] hover:underline flex items-center gap-1">
                Lihat Semua
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        </div>

        <div class="overflow-hidden">
            <table class="w-full border-separate border-spacing-y-3">
                <thead>
                    <tr class="text-white">
                        <th class="bg-[#03045E] p-4 rounded-l-full text-left font-bold tracking-widest text-xs uppercase pl-6">Judul</th>
                        <th class="bg-[#03045E] p-4 text-left font-bold tracking-widest text-xs uppercase">Pengirim</th>
                        <th class="bg-[#03045E] p-4 text-center font-bold tracking-widest text-xs uppercase">Target</th>
                        <th class="bg-[#03045E] p-4 rounded-r-full text-right font-bold tracking-widest text-xs uppercase pr-6">Waktu Dibuat</th>
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
                            {{ $pengumuman->created_at->translatedFormat('l, d F Y H:i') }}
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
    <script>
        function updateJam() {
            const now = new Date();

            const jam = now.toLocaleTimeString('id-ID', { 
                hour: '2-digit', 
                minute: '2-digit', 
                second: '2-digit' 
            });

            const hari = now.toLocaleDateString('id-ID', { 
                weekday: 'long', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            });

            document.getElementById('jam-realtime').textContent = jam;
            document.getElementById('tanggal-realtime').textContent = hari;
        }

        updateJam();
        setInterval(updateJam, 1000);
    </script>
</x-app-layout>