<div class="p-8 bg-white max-w-4xl mx-auto" id="printArea">
    <!-- The whole future lies in uncertainty: live immediately. - Seneca -->
    <div class="flex justify-between items-center border-b-4 border-[#03045E] pb-4 mb-8">
        <div>
            <h1 class="text-3xl font-black text-[#03045E]">EDUCONNECT</h1>
            <p class="text-sm uppercase tracking-widest">Laporan Kehadiran Siswa</p>
        </div>
        <div class="text-right text-sm">
            <p class="font-bold">{{ $siswa->nama_lengkap }}</p>
            <p>NISN: {{ $siswa->nisn }}</p>
            <p>Kelas: {{ $siswa->kelas->nama_kelas }}</p>
        </div>
    </div>

    <table class="w-full border-collapse">
        <thead>
            <tr class="bg-gray-100">
                <th class="border p-2">Tanggal</th>
                <th class="border p-2">Mata Pelajaran</th>
                <th class="border p-2">Status</th>
                <th class="border p-2">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($presensi as $p)
            <tr>
                <td class="border p-2 text-center">{{ $p->tanggal }}</td>
                <td class="border p-2">{{ $p->mapel->nama_mapel }}</td>
                <td class="border p-2 text-center font-bold">{{ $p->status }}</td>
                <td class="border p-2 text-sm">{{ $p->catatan ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <button onclick="window.print()" class="mt-8 px-6 py-2 bg-navy text-white rounded-full no-print">Cetak PDF</button>
</div>

<style>
    @media print { .no-print { display: none; } }
</style>
