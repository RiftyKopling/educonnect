<x-app-layout>
    <div class="max-w-3xl mx-auto">

        {{-- Breadcrumb --}}
        <div class="mb-8">
            <div class="flex items-center gap-2 text-sm text-gray-500 mb-2">
                <a href="{{ route('dashboard') }}" class="hover:text-[#03045E] font-medium">
                    Dashboard
                </a>
                <span>›</span>
                <a href="{{ route('konseling.index') }}" class="hover:text-[#03045E] font-medium">
                    Jadwal Konseling
                </a>
                <span>›</span>
                <span class="text-[#03045E] font-bold">
                    Edit Jadwal
                </span>
            </div>

            <a href="{{ route('konseling.index') }}"
                class="flex items-center gap-2 text-sm text-gray-500 hover:text-[#03045E] font-medium mb-4">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>

            <h2 class="text-3xl font-black text-[#03045E] tracking-tight">
                Edit Jadwal Konseling
            </h2>

            <p class="text-gray-500 text-sm mt-1">
                Perbarui jadwal, status, serta hasil konseling siswa.
            </p>
        </div>

        <div class="bg-white rounded-[2rem] shadow-sm p-8 border border-gray-100">

            <form action="{{ route('konseling.update', $konseling->id) }}" method="POST" class="space-y-6">

                @csrf
                @method('PUT')

                {{-- Session Error --}}
                @if(session('error'))
                <div id="notif-error"
                    class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-2xl flex items-center justify-between">

                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 9v2m0 4h.01M12 3a9 9 0 100 18A9 9 0 0012 3z" />
                        </svg>

                        <span class="font-medium">
                            {{ session('error') }}
                        </span>
                    </div>

                    <button type="button"
                        onclick="tutupNotifError()"
                        class="text-red-700 hover:text-red-900">

                        <svg class="w-5 h-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>

                    </button>

                </div>
                @endif

                {{-- Validation Error --}}
                @if($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-400 text-red-700 rounded-2xl">

                    <div class="flex items-center gap-2 mb-2">

                        <svg class="w-5 h-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">

                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 9v2m0 4h.01M12 3a9 9 0 100 18A9 9 0 0012 3z" />

                        </svg>

                        <span class="font-bold">
                            Terdapat {{ $errors->count() }} kesalahan:
                        </span>

                    </div>

                    <ul class="list-disc list-inside space-y-1 text-sm">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>

                </div>
                @endif

                {{-- Data Siswa --}}
                <div class="p-5 bg-gray-50 rounded-2xl border border-gray-200">

                    <div class="text-xs uppercase font-bold text-gray-500 mb-1">
                        Siswa Terjadwal
                    </div>

                    <div class="font-bold text-lg text-[#03045E]">
                        {{ $konseling->siswa->nama_lengkap }}
                        ({{ $konseling->siswa_nisn }})
                    </div>

                    <div class="text-sm text-gray-500">
                        Kelas :
                        {{ $konseling->siswa->kelas->nama_kelas ?? '-' }}
                    </div>

                </div>

                {{-- Tanggal dan Status --}}
                <div class="grid md:grid-cols-2 gap-6">

                    <div>

                        <label class="block text-sm font-bold text-[#03045E] mb-2 uppercase tracking-widest">
                            Tanggal & Waktu
                        </label>

                        <input
                            type="datetime-local"
                            name="tanggal"
                            value="{{ old('tanggal', \Carbon\Carbon::parse($konseling->tanggal)->format('Y-m-d\TH:i')) }}"
                            class="w-full bg-gray-50 border border-gray-200 rounded-2xl p-4 focus:ring-4 focus:ring-[#03045E]/20 focus:border-[#03045E] transition-all">

                        @error('tanggal')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror

                    </div>

                    <div>

                        <label class="block text-sm font-bold text-[#03045E] mb-2 uppercase tracking-widest">
                            Status
                        </label>

                        <select
                            name="status"
                            class="w-full bg-gray-50 border border-gray-200 rounded-2xl p-4 focus:ring-4 focus:ring-[#03045E]/20 focus:border-[#03045E] transition-all">

                            <option value="Terjadwal"
                                {{ old('status',$konseling->status)=='Terjadwal'?'selected':'' }}>
                                Terjadwal
                            </option>

                            <option value="Selesai"
                                {{ old('status',$konseling->status)=='Selesai'?'selected':'' }}>
                                Selesai
                            </option>

                            <option value="Batal"
                                {{ old('status',$konseling->status)=='Batal'?'selected':'' }}>
                                Batal
                            </option>

                        </select>

                        @error('status')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror

                    </div>

                </div>

                {{-- Jenis dan Topik --}}
                <div class="grid md:grid-cols-2 gap-6">

                    <div>

                        <label class="block text-sm font-bold text-[#03045E] mb-2 uppercase tracking-widest">
                            Jenis Layanan
                        </label>

                        <select
                            name="jenis_layanan"
                            class="w-full bg-gray-50 border border-gray-200 rounded-2xl p-4 focus:ring-4 focus:ring-[#03045E]/20 focus:border-[#03045E] transition-all">

                            @foreach([
                            'Konseling Pribadi',
                            'Konseling Kelompok',
                            'Bimbingan Karir',
                            'Bimbingan Belajar'
                            ] as $layanan)

                            <option value="{{ $layanan }}"
                                {{ old('jenis_layanan',$konseling->jenis_layanan)==$layanan?'selected':'' }}>
                                {{ $layanan }}
                            </option>

                            @endforeach

                        </select>

                        @error('jenis_layanan')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror

                    </div>

                    <div>

                        <label class="block text-sm font-bold text-[#03045E] mb-2 uppercase tracking-widest">
                            Topik Konseling
                        </label>

                        <input
                            type="text"
                            name="topik"
                            value="{{ old('topik',$konseling->topik) }}"
                            class="w-full bg-gray-50 border border-gray-200 rounded-2xl p-4 focus:ring-4 focus:ring-[#03045E]/20 focus:border-[#03045E] transition-all">

                        @error('topik')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror

                    </div>

                </div>

                {{-- Deskripsi --}}
                <div>

                    <label class="block text-sm font-bold text-[#03045E] mb-2 uppercase tracking-widest">
                        Deskripsi Kasus
                    </label>

                    <textarea
                        name="deskripsi_kasus"
                        rows="4"
                        class="w-full bg-gray-50 border border-gray-200 rounded-2xl p-4 focus:ring-4 focus:ring-[#03045E]/20 focus:border-[#03045E] transition-all">{{ old('deskripsi_kasus',$konseling->deskripsi_kasus) }}</textarea>

                    @error('deskripsi_kasus')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror

                </div>

                {{-- Hasil --}}
                <div>

                    <label class="block text-sm font-bold text-[#03045E] mb-2 uppercase tracking-widest">
                        Tindak Lanjut / Hasil
                    </label>

                    <textarea
                        name="tindak_lanjut"
                        rows="4"
                        class="w-full bg-gray-50 border border-gray-200 rounded-2xl p-4 focus:ring-4 focus:ring-[#03045E]/20 focus:border-[#03045E] transition-all">{{ old('tindak_lanjut',$konseling->tindak_lanjut) }}</textarea>

                    @error('tindak_lanjut')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror

                </div>

                {{-- Tombol --}}
                <div class="flex gap-4 pt-2">

                    <button
                        type="submit"
                        class="flex-1 py-4 bg-[#03045E] text-white rounded-2xl font-bold shadow-lg hover:bg-blue-900 transition-all uppercase tracking-widest">

                        Simpan Perubahan

                    </button>

                    <a href="{{ route('konseling.index') }}"
                        class="flex-1 py-4 text-center bg-gray-100 text-gray-600 rounded-2xl font-bold hover:bg-gray-200 transition-all uppercase tracking-widest">

                        Batal

                    </a>

                </div>

            </form>

        </div>

    </div>

    <script>
        function tutupNotifError() {
            const notif = document.getElementById('notif-error');

            if (notif) {
                notif.style.transition = 'opacity .5s';
                notif.style.opacity = '0';

                setTimeout(() => notif.remove(), 500);
            }
        }

        setTimeout(tutupNotifError, 5000);
    </script>

</x-app-layout>