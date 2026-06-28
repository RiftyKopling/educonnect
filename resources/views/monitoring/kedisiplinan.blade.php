<x-app-layout>
    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-4">
        <a href="{{ route('dashboard') }}" class="hover:text-[#03045E] font-medium flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Dashboard
        </a>
        <span>›</span>
        <span class="text-[#03045E] font-bold">Monitoring Kedisiplinan</span>
    </div>

    <!-- Header -->
    <div class="mb-6 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
        <div>
            <h2 class="text-3xl font-black text-[#03045E] tracking-tight">Monitoring Kedisiplinan</h2>
            <p class="text-gray-500 text-sm mt-1">Pantau kehadiran dan pelanggaran siswa secara menyeluruh.</p>
        </div>
        <a href="{{ route('monitoring.cetak-kedisiplinan') }}" target="_blank" class="px-6 py-3 bg-emerald-500 text-white rounded-full font-bold shadow-lg hover:scale-105 transition-all flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            CETAK LAPORAN
        </a>
    </div>

    <!-- Statistik Ringkas -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
        <div class="bg-white rounded-2xl shadow-sm p-5 text-center border border-gray-50">
            <div class="text-3xl font-black text-[#03045E]">{{ $totalSiswa }}</div>
            <div class="text-xs text-gray-400 font-bold mt-1 uppercase tracking-wider">Total Siswa</div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm p-5 text-center border border-gray-50">
            <div class="text-3xl font-black text-[#03045E]">{{ $totalKelas }}</div>
            <div class="text-xs text-gray-400 font-bold mt-1 uppercase tracking-wider">Total Kelas</div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm p-5 text-center border border-gray-50">
            <div class="text-3xl font-black text-emerald-600">{{ $persenHadir }}%</div>
            <div class="text-xs text-gray-400 font-bold mt-1 uppercase tracking-wider">Hadir Hari Ini</div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm p-5 text-center border border-gray-50">
            <div class="text-3xl font-black text-amber-500">{{ $presensiStats['sakit'] + $presensiStats['izin'] }}</div>
            <div class="text-xs text-gray-400 font-bold mt-1 uppercase tracking-wider">Sakit/Izin</div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm p-5 text-center border border-gray-50">
            <div class="text-3xl font-black text-red-500">{{ $presensiStats['alpa'] }}</div>
            <div class="text-xs text-gray-400 font-bold mt-1 uppercase tracking-wider">Alpa Hari Ini</div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm p-5 text-center border border-gray-50">
            <div class="text-3xl font-black text-red-600">{{ $pelanggaranBulanIni }}</div>
            <div class="text-xs text-gray-400 font-bold mt-1 uppercase tracking-wider">Pelanggaran Bulan Ini</div>
        </div>
    </div>

    <!-- Grafik Tren Kehadiran & Grafik Pelanggaran -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Tren Kehadiran 30 Hari -->
        <div class="lg:col-span-2 bg-white rounded-[2rem] shadow-sm p-6 border border-gray-50">
            <h3 class="text-lg font-black text-[#03045E] mb-4">Tren Kehadiran 30 Hari Terakhir</h3>
            <canvas id="chartTrenKehadiran" height="120"></canvas>
        </div>

        <!-- Grafik Pelanggaran per Kategori -->
        <div class="bg-white rounded-[2rem] shadow-sm p-6 border border-gray-50">
            <h3 class="text-lg font-black text-[#03045E] mb-4">Pelanggaran per Kategori</h3>
            <canvas id="chartPelanggaran" height="200"></canvas>
            @if($pelanggaranPerKategori->isEmpty())
                <p class="text-gray-400 text-center text-sm mt-4">Belum ada data pelanggaran bulan ini.</p>
            @endif
        </div>
    </div>

    <!-- Tabel: Top 5 Kelas Terajin & Terburuk + Top 5 Pelanggaran -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Top 5 Kelas Terajin -->
        <div class="bg-white rounded-[2rem] shadow-sm p-6 border border-gray-50">
            <h3 class="text-lg font-black text-[#03045E] mb-4 flex items-center gap-2">
                <span class="w-3 h-3 bg-emerald-500 rounded-full"></span> Top 5 Kelas Terajin
            </h3>
            <div class="space-y-3">
                @forelse($kelasTerajin as $k)
                <div class="flex justify-between items-center">
                    <span class="font-bold text-[#03045E]">{{ $k['nama_kelas'] }}</span>
                    <span class="px-3 py-1 rounded-full text-xs font-bold {{ $k['persen'] >= 90 ? 'bg-emerald-100 text-emerald-600' : ($k['persen'] >= 75 ? 'bg-amber-100 text-amber-600' : 'bg-red-100 text-red-600') }}">{{ $k['persen'] }}%</span>
                </div>
                @empty
                <p class="text-gray-400 text-sm text-center">Belum ada data.</p>
                @endforelse
            </div>
        </div>

        <!-- Top 5 Kelas Terburuk -->
        <div class="bg-white rounded-[2rem] shadow-sm p-6 border border-gray-50">
            <h3 class="text-lg font-black text-[#03045E] mb-4 flex items-center gap-2">
                <span class="w-3 h-3 bg-red-500 rounded-full"></span> 5 Kelas Kehadiran Terendah
            </h3>
            <div class="space-y-3">
                @forelse($kelasTerburuk as $k)
                <div class="flex justify-between items-center">
                    <span class="font-bold text-[#03045E]">{{ $k['nama_kelas'] }}</span>
                    <span class="px-3 py-1 rounded-full text-xs font-bold {{ $k['persen'] >= 90 ? 'bg-emerald-100 text-emerald-600' : ($k['persen'] >= 75 ? 'bg-amber-100 text-amber-600' : 'bg-red-100 text-red-600') }}">{{ $k['persen'] }}%</span>
                </div>
                @empty
                <p class="text-gray-400 text-sm text-center">Belum ada data.</p>
                @endforelse
            </div>
        </div>

        <!-- Top 5 Jenis Pelanggaran -->
        <div class="bg-white rounded-[2rem] shadow-sm p-6 border border-gray-50">
            <h3 class="text-lg font-black text-[#03045E] mb-4 flex items-center gap-2">
                <span class="w-3 h-3 bg-amber-500 rounded-full"></span> Top 5 Pelanggaran Terbanyak
            </h3>
            <div class="space-y-3">
                @forelse($topPelanggaran as $p)
                <div class="flex justify-between items-center gap-2">
                    <div>
                        <span class="font-bold text-[#03045E] text-sm">{{ $p->nama_pelanggaran }}</span>
                        <span class="ml-1 px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider
                            {{ $p->kategori == 'Ringan' ? 'bg-blue-100 text-blue-600' : ($p->kategori == 'Sedang' ? 'bg-amber-100 text-amber-600' : 'bg-red-100 text-red-600') }}">
                            {{ $p->kategori }}
                        </span>
                    </div>
                    <span class="font-black text-[#03045E] text-lg">{{ $p->total }}</span>
                </div>
                @empty
                <p class="text-gray-400 text-sm text-center">Belum ada data.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Presensi Hari Ini Detail -->
    <div class="bg-white rounded-[2rem] shadow-sm p-6 border border-gray-50">
        <h3 class="text-lg font-black text-[#03045E] mb-4">Distribusi Kehadiran Hari Ini</h3>
        @if($totalPresensiHariIni > 0)
        <div class="flex gap-2 h-8 rounded-full overflow-hidden">
            @if($presensiStats['hadir'] > 0)
            <div class="bg-emerald-500 flex items-center justify-center text-white text-xs font-bold" style="width: {{ ($presensiStats['hadir'] / $totalPresensiHariIni) * 100 }}%">
                H {{ $presensiStats['hadir'] }}
            </div>
            @endif
            @if($presensiStats['sakit'] > 0)
            <div class="bg-amber-400 flex items-center justify-center text-white text-xs font-bold" style="width: {{ ($presensiStats['sakit'] / $totalPresensiHariIni) * 100 }}%">
                S {{ $presensiStats['sakit'] }}
            </div>
            @endif
            @if($presensiStats['izin'] > 0)
            <div class="bg-blue-400 flex items-center justify-center text-white text-xs font-bold" style="width: {{ ($presensiStats['izin'] / $totalPresensiHariIni) * 100 }}%">
                I {{ $presensiStats['izin'] }}
            </div>
            @endif
            @if($presensiStats['alpa'] > 0)
            <div class="bg-red-500 flex items-center justify-center text-white text-xs font-bold" style="width: {{ ($presensiStats['alpa'] / $totalPresensiHariIni) * 100 }}%">
                A {{ $presensiStats['alpa'] }}
            </div>
            @endif
            @if($presensiStats['dispen'] > 0)
            <div class="bg-purple-400 flex items-center justify-center text-white text-xs font-bold" style="width: {{ ($presensiStats['dispen'] / $totalPresensiHariIni) * 100 }}%">
                D {{ $presensiStats['dispen'] }}
            </div>
            @endif
        </div>
        <div class="flex gap-4 mt-3 text-xs text-gray-500 font-medium flex-wrap">
            <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-emerald-500"></span> Hadir</span>
            <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-amber-400"></span> Sakit</span>
            <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-blue-400"></span> Izin</span>
            <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-red-500"></span> Alpa</span>
            <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-purple-400"></span> Dispensasi</span>
        </div>
        @else
        <p class="text-gray-400 text-sm text-center py-4">Belum ada data presensi hari ini.</p>
        @endif
    </div>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
    <script>
        // === TREN KEHADIRAN LINE CHART ===
        const trenData = @json($tren30Hari);
        const ctxTren = document.getElementById('chartTrenKehadiran').getContext('2d');
        new Chart(ctxTren, {
            type: 'line',
            data: {
                labels: trenData.map(d => d.tanggal),
                datasets: [{
                    label: '% Kehadiran',
                    data: trenData.map(d => d.persen),
                    borderColor: '#03045E',
                    backgroundColor: 'rgba(3, 4, 94, 0.08)',
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#03045E',
                    pointRadius: 3,
                    pointHoverRadius: 6,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: ctx => ctx.parsed.y + '% kehadiran'
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        ticks: { callback: v => v + '%' }
                    }
                }
            }
        });

        // === PELANGGARAN DOUGHNUT CHART ===
        const pelanggaranData = @json($pelanggaranPerKategori);
        const ctxPelanggaran = document.getElementById('chartPelanggaran').getContext('2d');
        const pelLabels = Object.keys(pelanggaranData);
        const pelValues = Object.values(pelanggaranData);
        const pelColors = pelLabels.map(l => {
            if (l === 'Ringan') return '#3b82f6';
            if (l === 'Sedang') return '#f59e0b';
            return '#ef4444';
        });

        if (pelLabels.length > 0) {
            new Chart(ctxPelanggaran, {
                type: 'doughnut',
                data: {
                    labels: pelLabels,
                    datasets: [{
                        data: pelValues,
                        backgroundColor: pelColors,
                        borderWidth: 0,
                    }]
                },
                options: {
                    responsive: true,
                    cutout: '65%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { font: { weight: 'bold', size: 11 } }
                        }
                    }
                }
            });
        }
    </script>
</x-app-layout>
