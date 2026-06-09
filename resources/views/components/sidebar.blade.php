@php
    $roleSlug = auth()->user()->role?->slug; 
    
    $menus = [
        'admin-sekolah' => [
            ['name' => 'Manajemen Pengguna', 'icon' => 'users'],
            ['name' => 'Manajemen Siswa', 'icon' => 'user-group'],
            ['name' => 'Manajemen Kelas', 'icon' => 'academic-cap'],
            ['name' => 'Manajemen Mapel', 'icon' => 'book-open'],
        ],
        'guru-mapel' => [
            ['name' => 'Presensi Siswa', 'icon' => 'clipboard-check'],
            ['name' => 'Input Nilai', 'icon' => 'pencil-square'],
            ['name' => 'Materi Ajar', 'icon' => 'document-text'],
        ],
        'guru-bk' => [
            ['name' => 'Catatan Konseling', 'icon' => 'chat-bubble-left-right'],
            ['name' => 'Laporan Perkembangan', 'icon' => 'chart-bar'],
        ],
        'wali-kelas' => [
            ['name' => 'Rekap Nilai', 'icon' => 'document-chart-bar'],
            ['name' => 'Rekap Presensi', 'icon' => 'clipboard-document-list'],
            ['name' => 'Catatan BK Siswa', 'icon' => 'folder-open'],
        ],
        'kepala-sekolah' => [
            ['name' => 'Laporan Akademik', 'icon' => 'presentation-chart-line'],
            ['name' => 'Monitoring Sistem', 'icon' => 'shield-check'],
            ['name' => 'Buat Pengumuman', 'icon' => 'megaphone'],
        ],
        'orang-tua' => [
            ['name' => 'Nilai Anak', 'icon' => 'academic-cap'],
            ['name' => 'Presensi Anak', 'icon' => 'check-badge'],
            ['name' => 'Pengumuman', 'icon' => 'bell'],
        ]
    ];
@endphp

<aside class="w-72 bg-white flex flex-col border-r border-gray-100 sticky top-0 h-screen">
    <div class="p-6 flex-1 overflow-y-auto">
        <nav class="space-y-3">
            @foreach($menus[$roleSlug] ?? [] as $item)
                <a href="#" class="flex items-center px-6 py-3 bg-navy text-white rounded-capsule shadow-lg transition-all hover:scale-105 active:scale-95">
                    <span class="text-sm font-semibold tracking-wide">{{ $item['name'] }}</span>
                </a>
            @endforeach
        </nav>
    </div>

    <div class="p-6 bg-white border-t border-gray-50">
        <div class="flex flex-col items-center">
            <div class="w-full">
                 <div class="p-3 bg-navy rounded-xl shadow-xl flex items-center justify-center">
                    <div class="text-white font-bold text-lg tracking-tighter">EduConnect</div>
                 </div>
            </div>
        </div>
    </div>
</aside>