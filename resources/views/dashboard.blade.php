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
                    Selamat Datang, {{ explode(' ', Auth::user()->name)[0] }}
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

    <div class="grid grid-cols-2 gap-6 mb-10" style="grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));">
        @php
            // Ambil slug dari relasi role, gunakan null safe operator (?->) untuk berjaga-jaga
            $userRoleSlug = auth()->user()->role?->slug;
        
            // Logic untuk Quick Cards menggunakan slug yang sesuai dengan database
            $cards = [
                'admin-sekolah' => [
                    ['name' => 'Manajemen Pengguna', 'icon' => 'users', 'route' => 'users.index'],
                    ['name' => 'Manajemen Siswa', 'icon' => 'user-group', 'route' => 'siswa.index'],
                    ['name' => 'Manajemen Kelas', 'icon' => 'academic-cap', 'route' => 'kelas.index'],
                    ['name' => 'Manajemen Mata Pelajaran', 'icon' => 'book-open', 'route' => 'mapel.index'],
                    ['name' => 'Manajemen Pengumuman', 'icon' => 'megaphone', 'route' => null],
                ],
                'guru-mapel' => [
                    ['name' => 'Presensi Siswa', 'icon' => 'clipboard-check', 'route' => null],
                    ['name' => 'Input Nilai', 'icon' => 'pencil-square', 'route' => null],
                    ['name' => 'Materi Ajar', 'icon' => 'document-text', 'route' => null],
                    ['name' => 'Pesan & Pengumuman', 'icon' => 'bell', 'route' => null],
                ],
                'guru-bk' => [
                    ['name' => 'Catatan Konseling', 'icon' => 'chat-bubble-left-right', 'route' => null],
                    ['name' => 'Laporan Perkembangan Siswa', 'icon' => 'chart-bar', 'route' => null],
                    ['name' => 'Pesan & Pengumuman', 'icon' => 'bell', 'route' => null],
                ],
                'wali-kelas' => [
                    ['name' => 'Rekap Nilai', 'icon' => 'document-chart-bar', 'route' => null],
                    ['name' => 'Rekap Presensi', 'icon' => 'clipboard-document-list', 'route' => null],
                    ['name' => 'Catatan BK Siswa', 'icon' => 'folder-open', 'route' => null],
                    ['name' => 'Profil Kelas', 'icon' => 'user-group', 'route' => null],
                    ['name' => 'Pesan & Pengumuman', 'icon' => 'bell', 'route' => null],
                ],
                'kepala-sekolah' => [
                    ['name' => 'Laporan Akademik', 'icon' => 'presentation-chart-line', 'route' => null],
                    ['name' => 'Pesan & Pengumuman', 'icon' => 'bell', 'route' => null],
                    ['name' => 'Buat Pengumuman', 'icon' => 'megaphone', 'route' => null],
                ],
                'orang-tua' => [
                    ['name' => 'Nilai Anak', 'icon' => 'academic-cap', 'route' => null],
                    ['name' => 'Presensi Anak', 'icon' => 'check-badge', 'route' => null],
                    ['name' => 'Pengumuman', 'icon' => 'bell', 'route' => null],
                ],
            ];
            
            // Cari kartu berdasarkan slug, jika tidak ada gunakan default
            $currentCards = $cards[$userRoleSlug] ?? [
                ['name' => 'Data Akademik', 'icon' => 'academic-cap', 'route' => null],
                ['name' => 'Presensi', 'icon' => 'clipboard-check', 'route' => null],
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

    <div class="bg-white rounded-[2rem] shadow-soft p-8">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-black text-navy tracking-tight">Daftar Pengumuman Terbaru</h3>
            <div class="flex gap-2">
                <input type="date" class="border-gray-200 rounded-capsule text-xs px-4 py-2 focus:ring-navy focus:border-navy">
            </div>
        </div>

        <div class="overflow-hidden">
            <table class="w-full border-separate border-spacing-y-3">
                <thead>
                    <tr class="text-white">
                        <th class="bg-gradient-to-b from-[#0077B6] to-[#03045E] p-4 rounded-l-full text-left font-bold tracking-widest text-sm uppercase ">Judul</th>
                        <th class="bg-gradient-to-b from-[#0077B6] to-[#03045E] p-4 rounded-r-full text-left font-bold tracking-widest text-sm uppercase">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="text-navy font-medium">
                    @php
                        $announcements = [
                            ['title' => 'Pelaksanaan UAS Sem Genap', 'date' => '2029-05-10'],
                            ['title' => 'Pemberitahuan Libur Covid-29', 'date' => '2029-04-02'],
                            ['title' => 'Pemberitahuan Libur Hari Raya Nyepi', 'date' => '2029-02-29'],
                            ['title' => 'Pelaksanaan UTS Sem Genap', 'date' => '2029-02-12'],
                        ];
                    @endphp
                    @foreach($announcements as $row)
                    <tr class="bg-gray-50 hover:bg-gray-100 transition-colors">
                        <td class="p-4 rounded-l-2xl shadow-sm">{{ $row['title'] }}</td>
                        <td class="p-4 rounded-r-2xl shadow-sm">{{ $row['date'] }}</td>
                    </tr>
                    @endforeach
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