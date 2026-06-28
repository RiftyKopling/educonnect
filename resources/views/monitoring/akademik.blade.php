<x-app-layout>
    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-4">
        <a href="{{ route('dashboard') }}" class="hover:text-[#03045E] font-medium flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Dashboard
        </a>
        <span>›</span>
        <span class="text-[#03045E] font-bold">Monitoring Akademik</span>
    </div>

    <!-- Header -->
    <div class="mb-6 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
        <div>
            <h2 class="text-3xl font-black text-[#03045E] tracking-tight">Monitoring Akademik</h2>
            <p class="text-gray-500 text-sm mt-1">Pantau kinerja akademik seluruh sekolah berdasarkan rekapitulasi nilai.</p>
        </div>
        <a href="{{ route('monitoring.cetak-akademik', ['tahun_ajaran' => $tahunAjaran, 'semester' => $semester]) }}" target="_blank" class="px-6 py-3 bg-emerald-500 text-white rounded-full font-bold shadow-lg hover:scale-105 transition-all flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            CETAK LAPORAN
        </a>
    </div>

    <!-- Filter -->
    <form method="GET" action="{{ route('monitoring.akademik') }}" class="flex gap-3 mb-6 flex-wrap">
        <select name="tahun_ajaran" class="rounded-full border-gray-200 px-6 py-3 focus:ring-[#03045E] focus:border-[#03045E] shadow-sm">
            @foreach($daftarTahunAjaran as $ta)
                <option value="{{ $ta }}" {{ $tahunAjaran == $ta ? 'selected' : '' }}>{{ $ta }}</option>
            @endforeach
            @if($daftarTahunAjaran->isEmpty())
                <option value="{{ date('Y') . '/' . (date('Y') + 1) }}">{{ date('Y') . '/' . (date('Y') + 1) }}</option>
            @endif
        </select>

        <select name="semester" class="rounded-full border-gray-200 px-6 py-3 focus:ring-[#03045E] focus:border-[#03045E] shadow-sm">
            <option value="Ganjil" {{ $semester == 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
            <option value="Genap" {{ $semester == 'Genap' ? 'selected' : '' }}>Genap</option>
        </select>

        <button type="submit" class="px-6 py-3 bg-[#03045E] text-white rounded-full font-bold shadow-lg hover:scale-105 transition-all">
            Terapkan
        </button>
    </form>

    <!-- Status Kelengkapan Input -->
    <div class="bg-white rounded-[2rem] shadow-sm p-6 border border-gray-50 mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h3 class="text-lg font-black text-[#03045E]">Status Kelengkapan Input Nilai</h3>
                <p class="text-gray-400 text-sm">{{ $filledCombinations }} dari {{ $expectedCombinations }} kombinasi Kelas × Mapel sudah terisi.</p>
            </div>
            <div class="text-3xl font-black {{ $persenKelengkapan >= 80 ? 'text-emerald-600' : ($persenKelengkapan >= 50 ? 'text-amber-500' : 'text-red-500') }}">
                {{ $persenKelengkapan }}%
            </div>
        </div>
        <div class="mt-3 h-4 bg-gray-100 rounded-full overflow-hidden">
            <div class="h-full rounded-full transition-all duration-500 {{ $persenKelengkapan >= 80 ? 'bg-emerald-500' : ($persenKelengkapan >= 50 ? 'bg-amber-400' : 'bg-red-400') }}"
                 style="width: {{ $persenKelengkapan }}%"></div>
        </div>
    </div>

    <!-- Grafik Rata-rata per Tingkat & per Mapel -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Rata-rata Nilai per Tingkat -->
        <div class="bg-white rounded-[2rem] shadow-sm p-6 border border-gray-50">
            <h3 class="text-lg font-black text-[#03045E] mb-4">Rata-rata Nilai per Tingkat Kelas</h3>
            @if($rataPerTingkat->isNotEmpty())
                <canvas id="chartPerTingkat" height="180"></canvas>
            @else
                <p class="text-gray-400 text-sm text-center py-8">Belum ada data nilai untuk filter ini.</p>
            @endif
        </div>

        <!-- Rata-rata Nilai per Mata Pelajaran -->
        <div class="bg-white rounded-[2rem] shadow-sm p-6 border border-gray-50">
            <h3 class="text-lg font-black text-[#03045E] mb-4">Rata-rata Nilai per Mata Pelajaran</h3>
            @if($rataPerMapel->isNotEmpty())
                <canvas id="chartPerMapel" height="180"></canvas>
            @else
                <p class="text-gray-400 text-sm text-center py-8">Belum ada data nilai untuk filter ini.</p>
            @endif
        </div>
    </div>

    <!-- Tabel Top & Bottom Kelas -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Top 5 Kelas -->
        <div class="bg-white rounded-[2rem] shadow-sm p-6 border border-gray-50">
            <h3 class="text-lg font-black text-[#03045E] mb-4 flex items-center gap-2">
                <span class="w-3 h-3 bg-emerald-500 rounded-full"></span> Top 5 Kelas Peringkat Tertinggi
            </h3>
            <div class="overflow-x-auto">
                <table class="w-full border-separate border-spacing-y-2">
                    <thead>
                        <tr class="text-white text-xs uppercase tracking-widest">
                            <th class="bg-[#03045E] p-3 rounded-l-full text-left pl-5">Kelas</th>
                            <th class="bg-[#03045E] p-3 text-center">Jumlah Siswa</th>
                            <th class="bg-[#03045E] p-3 rounded-r-full text-center pr-5">Rata-rata</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kelasTop as $kt)
                        <tr class="bg-gray-50">
                            <td class="p-3 rounded-l-xl pl-5 font-bold text-[#03045E]">{{ $kt->nama_kelas }}</td>
                            <td class="p-3 text-center text-gray-500 text-sm">{{ $kt->jumlah_siswa }}</td>
                            <td class="p-3 rounded-r-xl text-center">
                                <span class="px-3 py-1 rounded-full font-bold text-xs {{ $kt->avg_total >= 75 ? 'bg-emerald-100 text-emerald-600' : 'bg-red-100 text-red-600' }}">{{ $kt->avg_total }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center text-gray-400 p-4">Belum ada data.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Bottom 5 Kelas -->
        <div class="bg-white rounded-[2rem] shadow-sm p-6 border border-gray-50">
            <h3 class="text-lg font-black text-[#03045E] mb-4 flex items-center gap-2">
                <span class="w-3 h-3 bg-red-500 rounded-full"></span> 5 Kelas Peringkat Terendah
            </h3>
            <div class="overflow-x-auto">
                <table class="w-full border-separate border-spacing-y-2">
                    <thead>
                        <tr class="text-white text-xs uppercase tracking-widest">
                            <th class="bg-[#03045E] p-3 rounded-l-full text-left pl-5">Kelas</th>
                            <th class="bg-[#03045E] p-3 text-center">Jumlah Siswa</th>
                            <th class="bg-[#03045E] p-3 rounded-r-full text-center pr-5">Rata-rata</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kelasBawah as $kb)
                        <tr class="bg-gray-50">
                            <td class="p-3 rounded-l-xl pl-5 font-bold text-[#03045E]">{{ $kb->nama_kelas }}</td>
                            <td class="p-3 text-center text-gray-500 text-sm">{{ $kb->jumlah_siswa }}</td>
                            <td class="p-3 rounded-r-xl text-center">
                                <span class="px-3 py-1 rounded-full font-bold text-xs {{ $kb->avg_total >= 75 ? 'bg-emerald-100 text-emerald-600' : 'bg-red-100 text-red-600' }}">{{ $kb->avg_total }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center text-gray-400 p-4">Belum ada data.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Tabel Kinerja Lengkap per Kelas -->
    <div class="bg-white rounded-[2rem] shadow-sm p-6 border border-gray-50">
        <h3 class="text-lg font-black text-[#03045E] mb-4">Kinerja Akademik Seluruh Kelas</h3>
        <div class="overflow-x-auto">
            <table class="w-full border-separate border-spacing-y-3 min-w-[600px]">
                <thead>
                    <tr class="text-white text-xs uppercase tracking-widest">
                        <th class="bg-[#03045E] p-3 rounded-l-full text-left pl-6">Kelas</th>
                        <th class="bg-[#03045E] p-3 text-center">Tingkat</th>
                        <th class="bg-[#03045E] p-3 text-center">Jumlah Siswa</th>
                        <th class="bg-[#03045E] p-3 rounded-r-full text-center pr-6">Rata-rata Nilai</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kinerjaPerkelas as $kp)
                    <tr class="bg-gray-50 hover:bg-white hover:shadow-md transition-all">
                        <td class="p-3 rounded-l-2xl pl-6 font-bold text-[#03045E]">{{ $kp->nama_kelas }}</td>
                        <td class="p-3 text-center text-gray-500">{{ $kp->tingkat }}</td>
                        <td class="p-3 text-center text-gray-500">{{ $kp->jumlah_siswa }}</td>
                        <td class="p-3 rounded-r-2xl text-center pr-6">
                            <span class="px-3 py-1 rounded-full font-bold text-xs {{ $kp->avg_total >= 75 ? 'bg-emerald-100 text-emerald-600' : ($kp->avg_total >= 60 ? 'bg-amber-100 text-amber-600' : 'bg-red-100 text-red-600') }}">{{ $kp->avg_total }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center text-gray-400 p-8">Belum ada data nilai untuk filter ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
    <script>
        // === RATA-RATA NILAI PER TINGKAT (BAR CHART) ===
        @if($rataPerTingkat->isNotEmpty())
        const tingkatData = @json($rataPerTingkat);
        const ctxTingkat = document.getElementById('chartPerTingkat').getContext('2d');
        new Chart(ctxTingkat, {
            type: 'bar',
            data: {
                labels: tingkatData.map(d => 'Kelas ' + d.tingkat),
                datasets: [
                    {
                        label: 'Tugas',
                        data: tingkatData.map(d => d.avg_tugas),
                        backgroundColor: 'rgba(3, 4, 94, 0.8)',
                        borderRadius: 6,
                    },
                    {
                        label: 'Kuis',
                        data: tingkatData.map(d => d.avg_kuis),
                        backgroundColor: 'rgba(0, 119, 182, 0.8)',
                        borderRadius: 6,
                    },
                    {
                        label: 'UTS',
                        data: tingkatData.map(d => d.avg_uts),
                        backgroundColor: 'rgba(0, 180, 216, 0.8)',
                        borderRadius: 6,
                    },
                    {
                        label: 'UAS',
                        data: tingkatData.map(d => d.avg_uas),
                        backgroundColor: 'rgba(144, 224, 239, 0.9)',
                        borderRadius: 6,
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { font: { weight: 'bold', size: 11 } }
                    }
                },
                scales: {
                    y: { beginAtZero: true, max: 100, ticks: { stepSize: 25 } }
                }
            }
        });
        @endif

        // === RATA-RATA NILAI PER MAPEL (HORIZONTAL BAR CHART) ===
        @if($rataPerMapel->isNotEmpty())
        const mapelData = @json($rataPerMapel);
        const ctxMapel = document.getElementById('chartPerMapel').getContext('2d');
        new Chart(ctxMapel, {
            type: 'bar',
            data: {
                labels: mapelData.map(d => d.nama_mapel),
                datasets: [{
                    label: 'Rata-rata',
                    data: mapelData.map(d => d.avg_total),
                    backgroundColor: mapelData.map(d => d.avg_total >= 75 ? 'rgba(16, 185, 129, 0.8)' : (d.avg_total >= 60 ? 'rgba(245, 158, 11, 0.8)' : 'rgba(239, 68, 68, 0.8)')),
                    borderRadius: 6,
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: { beginAtZero: true, max: 100 }
                }
            }
        });
        @endif
    </script>
</x-app-layout>
