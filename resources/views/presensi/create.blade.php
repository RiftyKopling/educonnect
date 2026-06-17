<x-app-layout>
    <!-- Let all your things have their places; let each part of your business have its time. - Benjamin Franklin -->
    <div class="bg-white rounded-[2rem] shadow-sm p-8 border border-gray-100">
        <h2 class="text-2xl font-black text-[#03045E] mb-6 uppercase tracking-tight">Input Presensi Kolektif</h2>

        <form action="{{ route('presensi.create') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            <select name="kelas_id" onchange="this.form.submit()" class="rounded-2xl border-gray-200 focus:ring-[#03045E]">
                <option value="">Pilih Kelas</option>
                @foreach($kelas as $k)
                    <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                @endforeach
            </select>
        </form>

        @if(count($siswa) > 0)
        <form action="{{ route('presensi.store') }}" method="POST">
            @csrf
            <input type="hidden" name="kelas_id" value="{{ request('kelas_id') }}">
            <div class="grid grid-cols-2 gap-4 mb-6">
                <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" class="rounded-2xl border-gray-200 p-4 font-bold">
                <select name="mapel_id" required class="rounded-2xl border-gray-200">
                    <option value="">Pilih Mata Pelajaran</option>
                    @foreach($mapel as $m) <option value="{{ $m->id }}">{{ $m->nama_mapel }}</option> @endforeach
                </select>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full border-separate border-spacing-y-2">
                    <thead class="bg-[#03045E] text-white">
                        <tr>
                            <th class="p-4 rounded-l-2xl">Siswa</th>
                            <th class="p-4">Kehadiran (H / S / I / A / D)</th>
                            <th class="p-4 rounded-r-2xl">Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($siswa as $s)
                        <tr class="bg-gray-50">
                            <td class="p-4 rounded-l-2xl font-bold">{{ $s->nama_lengkap }}</td>
                            <td class="p-4">
                                <div class="flex gap-4">
                                    @foreach(['H','S','I','A','D'] as $st)
                                    <label class="flex items-center gap-1 cursor-pointer">
                                        <input type="radio" name="status[{{ $s->nisn }}]" value="{{ $st }}" 
                                            {{ $st == 'H' ? 'checked' : '' }} class="text-[#03045E] focus:ring-[#03045E]">
                                        <span class="text-xs font-bold">{{ $st }}</span>
                                    </label>
                                    @endforeach
                                </div>
                            </td>
                            <td class="p-4 rounded-r-2xl">
                                <input type="text" name="catatan[{{ $s->nisn }}]" placeholder="..." class="w-full text-xs rounded-xl border-gray-200">
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <button type="submit" class="mt-8 w-full py-4 bg-[#03045E] text-white rounded-full font-black shadow-xl uppercase tracking-widest">Simpan Presensi</button>
        </form>
        @endif
    </div>
</x-app-layout>