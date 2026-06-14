@php
    $roleSlug = auth()->user()->role?->slug; 
    
    // Saya menambahkan kunci 'route' ke dalam array ini
    $menus = [
        'admin-sekolah' => [
            ['name' => 'Manajemen Pengguna', 'icon' => 'users', 'route' => 'users.index'],
            ['name' => 'Manajemen Siswa', 'icon' => 'user-group', 'route' => 'siswa.index'],
            ['name' => 'Manajemen Kelas', 'icon' => 'academic-cap', 'route' => 'kelas.index'],
            ['name' => 'Manajemen Mapel', 'icon' => 'book-open', 'route' => 'mapel.index'],
        ],
        'guru-mapel' => [
            ['name' => 'Presensi Siswa', 'icon' => 'clipboard-check', 'route' => null],
            ['name' => 'Input Nilai', 'icon' => 'pencil-square', 'route' => null],
            ['name' => 'Materi Ajar', 'icon' => 'document-text', 'route' => null],
            ['name' => 'Manajemen Pengumuman', 'icon' => 'megaphone', 'route' => 'pengumuman.index'],
            ['name' => 'Papan Pengumuman', 'icon' => 'bell', 'route' => 'pengumuman.masuk'],
        ],
        'guru-bk' => [
            ['name' => 'Catatan Konseling', 'icon' => 'chat-bubble-left-right', 'route' => null],
            ['name' => 'Laporan Perkembangan', 'icon' => 'chart-bar', 'route' => null],
            ['name' => 'Manajemen Pengumuman', 'icon' => 'megaphone', 'route' => 'pengumuman.index'],
            ['name' => 'Papan Pengumuman', 'icon' => 'bell', 'route' => 'pengumuman.masuk'],
        ],
        'wali-kelas' => [
            ['name' => 'Rekap Nilai', 'icon' => 'document-chart-bar', 'route' => null],
            ['name' => 'Rekap Presensi', 'icon' => 'clipboard-document-list', 'route' => null],
            ['name' => 'Catatan BK Siswa', 'icon' => 'folder-open', 'route' => null],
            ['name' => 'Manajemen Pengumuman', 'icon' => 'megaphone', 'route' => 'pengumuman.index'],
            ['name' => 'Papan Pengumuman', 'icon' => 'bell', 'route' => 'pengumuman.masuk'],
        ],
        'kepala-sekolah' => [
            ['name' => 'Laporan Akademik', 'icon' => 'presentation-chart-line', 'route' => null],
            ['name' => 'Monitoring Sistem', 'icon' => 'shield-check', 'route' => null],
            ['name' => 'Manajemen Pengumuman', 'icon' => 'megaphone', 'route' => 'pengumuman.index'],
            ['name' => 'Papan Pengumuman', 'icon' => 'bell', 'route' => 'pengumuman.masuk'],
        ],
        'orang-tua' => [
            ['name' => 'Nilai Anak', 'icon' => 'academic-cap', 'route' => null],
            ['name' => 'Presensi Anak', 'icon' => 'check-badge', 'route' => null],
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
                
                <a href="{{ $url }}" class="flex items-center px-6 py-3 bg-navy text-white rounded-capsule shadow-lg transition-all hover:scale-105 active:scale-95">
                    <span class="text-sm font-semibold tracking-wide">{{ $item['name'] }}</span>
                </a>
            @endforeach
        </nav>
    </div>

    <div class="p-4 bg-white border-t border-gray-50">
        <div class="flex flex-col items-center">
            <div class="mt-auto w-full">
                <div class="flex justify-center" style="margin-bottom: 5px;">
                    <img src="{{ asset('images/logo_educonnect.png') }}" alt="Logo" style="width: 360px;" class="max-w-none">
                </div>
            </div>
        </div>
    </div>
</aside>