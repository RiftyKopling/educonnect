@php
    $roleSlug = auth()->user()->role?->slug; 
    
    $menus = [
        'admin-sekolah' => [
            ['name' => 'Manajemen Pengguna', 'icon' => 'users', 'route' => 'users.index'],
            ['name' => 'Manajemen Siswa', 'icon' => 'user-group', 'route' => 'siswa.index'],
            ['name' => 'Manajemen Kelas', 'icon' => 'academic-cap', 'route' => 'kelas.index'],
            ['name' => 'Manajemen Mata Pelajaran', 'icon' => 'book-open', 'route' => 'mapel.index'],
            ['name' => 'Manajemen Pengumuman', 'icon' => 'megaphone', 'route' => 'pengumuman.index'],
            ['name' => 'Papan Pengumuman', 'icon' => 'bell', 'route' => 'pengumuman.masuk'],
        ],
        'guru-mapel' => [
            ['name' => 'Presensi Siswa', 'icon' => 'clipboard-document-check', 'route' => 'presensi.index'],
            ['name' => 'Input Nilai', 'icon' => 'pencil-square', 'route' => 'nilai.index'],
            ['name' => 'Materi Ajar', 'icon' => 'document-text', 'route' => 'materi-ajar.index'],
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
            ['name' => 'Profil Kelas', 'icon' => 'user-group', 'route' => 'profil-kelas.index'],
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
        ]
    ];
@endphp

<aside class="w-72 shrink-0 bg-white flex flex-col border-r border-gray-100 sticky top-0 h-screen transition-all duration-300 ease-in-out" :class="sidebarOpen ? 'ml-0' : '-ml-72'">
    <div class="p-6 flex-1 overflow-y-auto">
        <nav class="space-y-3">
            @foreach($menus[$roleSlug] ?? [] as $item)
                @php
                    // Logika pintar: Jika route ada isinya, buatkan link. Jika null, kembalikan ke '#'
                    $url = $item['route'] ? route($item['route']) : '#';
                @endphp
                <a href="{{ $url }}" class="flex items-center gap-3 px-6 py-3 bg-gradient-to-bl from-[#0077B6] to-[#03045E] text-white rounded-capsule shadow-lg transition-all hover:scale-105 active:scale-95">
                @svg('heroicon-o-' . $item['icon'], 'w-5 h-5 shrink-0')
                    <span class="text-sm font-semibold tracking-wide">{{ $item['name'] }}</span>
                </a>
            @endforeach
        </nav>
    </div>

    <div class="p-4 bg-white border-t border-gray-50">
        <div class="flex flex-col items-center">
            <div class="mt-auto w-full">
                <div class="flex justify-center items-center gap-2 mb-8">
                    <img src="{{ asset('images/fix_logo_educonnect.png') }}" alt="Logo EduConnect" style="width: 100px; margin-right: 12px;">
                    <div style="width: 1px; height: 60px; background-color: #d1d5db;"></div>
                    <img src="{{ asset('images/fix_logo_smpn2mungkid.png') }}" alt="Logo Sekolah" style="width: 100px; margin-left: 12px;">
                </div>
            </div>
        </div>
    </div>
</aside>