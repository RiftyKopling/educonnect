<x-app-layout>
    <div class="relative overflow-hidden bg-gradient-to-br from-[#00B4D8] via-[#0077B6] to-[#03045E] rounded-3xl p-10 text-white shadow-2xl mb-8">
        <div class="relative z-10">
            <h2 class="text-4xl font-extrabold mb-2">Selamat Datang, {{ explode(' ', Auth::user()->name)[0] }}</h2>
            <p class="text-white/80 font-light tracking-wide italic">Pantau dan kelola aktivitas sekolah dalam satu platform terpadu.</p>
        </div>
        <div class="absolute -right-20 -top-20 w-80 h-80 bg-white/10 rounded-full blur-3xl"></div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
        @php
            // Logic untuk Quick Cards menggunakan slug yang sesuai dengan database
            $cards = [
                'admin-sekolah' => ['Pengguna', 'Siswa', 'Kelas', 'Mapel'],
                'guru-mapel'    => ['Input Nilai', 'Presensi', 'Materi', 'Jadwal'],
                'orang-tua'     => ['Nilai Anak', 'Presensi', 'Laporan BK', 'Pesan'],
            ];
            
            // Ambil slug dari relasi role, gunakan null safe operator (?->) untuk berjaga-jaga
            $userRoleSlug = auth()->user()->role?->slug;
            
            // Cari kartu berdasarkan slug, jika tidak ada gunakan default
            $currentCards = $cards[$userRoleSlug] ?? ['Data Akademik', 'Presensi', 'Konseling', 'Pengumuman'];
        @endphp

        @foreach($currentCards as $card)
        <div class="bg-white p-6 rounded-[2.5rem] shadow-soft border border-gray-50 flex flex-col items-center justify-center gap-3 transition-transform hover:-translate-y-1 hover:shadow-xl cursor-pointer">
            <div class="w-12 h-12 bg-gray-100 rounded-2xl flex items-center justify-center text-navy">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16m-7 6h7"></path></svg>
            </div>
            <span class="text-navy font-bold text-sm tracking-tight">{{ $card }}</span>
        </div>
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
                        <th class="bg-navy p-4 rounded-l-full text-left font-bold tracking-widest text-xs uppercase">Judul</th>
                        <th class="bg-navy p-4 rounded-r-full text-left font-bold tracking-widest text-xs uppercase">Tanggal</th>
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
</x-app-layout>