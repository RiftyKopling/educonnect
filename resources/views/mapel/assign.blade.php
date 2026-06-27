<x-app-layout>
    <div class="max-w-4xl mx-auto">
        <!-- Breadcrumb -->
        <div class="mb-8">
            <div class="flex items-center gap-2 text-sm text-gray-500 mb-2">
                <a href="{{ route('dashboard') }}" class="hover:text-[#03045E] font-medium">
                    Dashboard
                </a>
                <span>›</span>
                <a href="{{ route('mapel.index') }}" class="hover:text-[#03045E] font-medium">Manajemen Mata Pelajaran</a>
                <span>›</span>
                <span class="text-[#03045E] font-bold">Assign Guru</span>
            </div>
            <a href="{{ route('mapel.index') }}" class="flex items-center gap-2 text-sm text-gray-500 hover:text-[#03045E] font-medium mb-4">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali
            </a>
            <h2 class="text-3xl font-black text-[#03045E] tracking-tight">Assign Guru Pengampu</h2>
            <p class="text-gray-500 text-sm mt-1">
                Menentukan guru yang mengampu mata pelajaran: <span class="font-bold text-[#03045E]">{{ $mapel->nama_mapel }}</span>
                <span class="text-gray-400">({{ $mapel->kode_mapel }})</span>
            </p>
        </div>

        <!-- Notifikasi Success -->
        @if(session('success'))
            <div id="notif-sukses" class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-2xl flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
                <button onclick="tutupNotif()" class="text-green-700 hover:text-green-900 ml-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        @endif

        <!-- Notifikasi Error -->
        @if(session('error'))
            <div id="notif-error" class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-2xl flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M12 3a9 9 0 100 18A9 9 0 0012 3z"/>
                    </svg>
                    <span class="font-medium">{{ session('error') }}</span>
                </div>
                <button onclick="tutupNotifError()" class="text-red-700 hover:text-red-900 ml-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        @endif

        <!-- Error Handling -->
        @if($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-400 text-red-700 rounded-2xl">
                <div class="flex items-center gap-2 mb-2">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M12 3a9 9 0 100 18A9 9 0 0012 3z"/>
                    </svg>
                    <span class="font-bold">Terdapat {{ $errors->count() }} kesalahan:</span>
                </div>
                <ul class="list-disc list-inside space-y-1 text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-[2rem] shadow-sm p-8 border border-gray-100">
            <form action="{{ route('mapel.storeAssign', $mapel->id) }}" method="POST">
                @csrf
                
                <!-- Informasi Mapel -->
                <div class="mb-6 p-4 bg-gray-50 rounded-2xl border border-gray-200 flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Mata Pelajaran</p>
                        <p class="text-lg font-bold text-[#03045E]">{{ $mapel->nama_mapel }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Kode</p>
                        <p class="text-lg font-bold text-[#03045E]">{{ $mapel->kode_mapel }}</p>
                    </div>
                </div>

                <h3 class="text-xl font-bold text-[#03045E] mb-4">Pilih Guru Pengampu</h3>
                <p class="text-sm text-gray-400 mb-6">Centang guru yang akan mengampu mata pelajaran ini</p>
                
                <!-- Guru yang Sudah Dipilih -->
                @if($mapel->gurus->isNotEmpty())
                    <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-2xl">
                        <p class="text-sm font-bold text-green-700 flex items-center gap-2 flex-wrap">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Guru saat ini: 
                            @foreach($mapel->gurus as $guru)
                                <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-bold">{{ $guru->name }}</span>
                            @endforeach
                        </p>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
                    @forelse($gurus as $guru)
                        @php
                            $isChecked = $mapel->gurus->contains($guru->id);
                        @endphp
                        <label class="flex items-start p-4 border-2 rounded-2xl cursor-pointer transition-all hover:shadow-md {{ $isChecked ? 'border-[#03045E] bg-[#03045E]/5' : 'border-gray-200 hover:border-[#03045E]/30' }}">
                            <input type="checkbox" 
                                name="guru_ids[]" 
                                value="{{ $guru->id }}" 
                                class="w-5 h-5 mt-0.5 text-[#03045E] rounded border-gray-300 focus:ring-[#03045E] focus:ring-2"
                                {{ $isChecked ? 'checked' : '' }}>
                            <div class="ml-3">
                                <p class="font-bold text-[#03045E]">{{ $guru->name }}</p>
                                <p class="text-sm text-gray-500">{{ $guru->email }}</p>
                                @if($isChecked)
                                    <span class="text-xs text-green-600 font-bold">✓ Terpilih</span>
                                @endif
                            </div>
                        </label>
                    @empty
                        <div class="col-span-full p-6 bg-yellow-50 border border-yellow-200 rounded-2xl text-center">
                            <svg class="w-12 h-12 text-yellow-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            <p class="font-medium text-yellow-700">Belum ada user dengan role Guru Mata Pelajaran di sistem.</p>
                            <p class="text-sm text-yellow-600 mt-1">Silakan tambahkan user dengan role Guru Mata Pelajaran terlebih dahulu.</p>
                            <a href="{{ route('users.create') }}" class="inline-block mt-3 px-4 py-2 bg-yellow-500 text-white rounded-full text-sm font-bold hover:bg-yellow-600 transition-all">
                                Tambah Guru
                            </a>
                        </div>
                    @endforelse
                </div>

                <!-- Tombol Aksi -->
                <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                    <a href="{{ route('mapel.index') }}" 
                    class="px-8 py-3 bg-gray-200 text-gray-700 rounded-full font-bold hover:bg-gray-300 transition-all">
                        Batal
                    </a>
                    <button type="submit" 
                            class="px-8 py-3 bg-[#03045E] text-white rounded-full font-bold shadow-lg hover:scale-105 transition-all flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Simpan Penugasan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function tutupNotif() {
            const notif = document.getElementById('notif-sukses');
            if (notif) {
                notif.style.transition = 'opacity 0.5s';
                notif.style.opacity = '0';
                setTimeout(() => notif.remove(), 500);
            }
        }

        function tutupNotifError() {
            const notif = document.getElementById('notif-error');
            if (notif) {
                notif.style.transition = 'opacity 0.5s';
                notif.style.opacity = '0';
                setTimeout(() => notif.remove(), 500);
            }
        }

        // Auto hilang setelah 5 detik
        setTimeout(tutupNotif, 5000);
        setTimeout(tutupNotifError, 5000);
    </script>
</x-app-layout>