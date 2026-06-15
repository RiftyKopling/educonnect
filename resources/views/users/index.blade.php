<x-app-layout>
    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-4">
        <a href="{{ route('dashboard') }}" class="hover:text-[#03045E] font-medium flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Dashboard
        </a>
        <span>›</span>
        <span class="text-[#03045E] font-bold">Manajemen Pengguna</span>
    </div>

    <!-- Header -->
    <div class="mb-6 flex justify-between items-center">
        <div> 
            <h2 class="text-3xl font-black text-[#03045E] tracking-tight">Manajemen Pengguna</h2>
            <p class="text-gray-500">Mengelola informasi akun sekolah serta mengatur hak akses dan wewenang pengguna secara terpusat.</p>
        </div>

        <a href="{{ route('users.create') }}" class="px-6 py-3 bg-[#03045E] text-white rounded-full font-bold shadow-lg hover:scale-105 transition-all flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            TAMBAH PENGGUNA
        </a>
    </div>

    <!-- Search & Filter -->
    <form method="GET" action="{{ route('users.index') }}" class="flex gap-3 mb-6">
        <div class="flex-1 relative">
            <input type="text" 
                name="search" 
                value="{{ request('search') }}"
                placeholder="Cari nama atau email..."
                class="w-full rounded-full border-gray-200 pl-12 pr-4 py-3 focus:ring-[#03045E] focus:border-[#03045E] shadow-sm">
            <svg class="w-5 h-5 absolute left-4 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        </div>

        <select name="role" class="rounded-full border-gray-200 px-6 py-3 focus:ring-[#03045E] focus:border-[#03045E] shadow-sm">
            <option value="">Semua Role</option>
            @foreach($roles as $role)
                <option value="{{ $role->id }}" {{ request('role') == $role->id ? 'selected' : '' }}>
                    {{ strtoupper(str_replace('-', ' ', $role->name)) }}
                </option>
            @endforeach
        </select>

        <button type="submit" class="px-6 py-3 bg-[#03045E] text-white rounded-full font-bold shadow-lg hover:scale-105 transition-all">
            Cari
        </button>

        @if(request('search') || request('role'))
            <a href="{{ route('users.index') }}" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-full font-bold shadow-sm hover:scale-105 transition-all">
                Reset
            </a>
        @endif
    </form>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded-r-xl">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-[2rem] shadow-sm overflow-hidden p-6">
        <table class="w-full border-separate border-spacing-y-3">
            <thead>
                <tr class="text-white text-sm uppercase tracking-widest">
                    <th class="bg-[#03045E] p-4 rounded-l-full text-left">Nama</th>
                    <th class="bg-[#03045E] p-4 text-left">Email</th>
                    <th class="bg-[#03045E] p-4 text-left">Role</th>
                    <th class="bg-[#03045E] p-4 rounded-r-full text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-[#03045E] font-medium">
                @forelse($users as $user)
                <tr class="bg-gray-50 hover:bg-gray-100 transition-all">
                    <td class="p-4 rounded-l-2xl">{{ $user->name }}</td>
                    <td class="p-4">{{ $user->email }}</td>
                    <td class="p-4">
                        <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-bold uppercase">
                            {{ str_replace('-', ' ', $user->role->name ?? 'N/A') }}
                        </span>
                    </td>
                    <td class="p-4 rounded-r-2xl text-center">
                        <div class="flex justify-center gap-2">
                            <a href="{{ route('users.edit', $user) }}" class="p-2 bg-amber-100 text-amber-600 rounded-xl hover:bg-amber-200 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </a>
                            <button 
                                type="button"
                                data-url="{{ route('users.destroy', $user) }}"
                                data-nama="{{ $user->name }}"
                                onclick="bukaModal(this.dataset.url, this.dataset.nama)"
                                class="p-2 bg-red-100 text-red-600 rounded-xl hover:bg-red-200 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="p-8 text-center text-gray-400">
                        <div class="flex flex-col items-center gap-2">
                            <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <span class="font-bold text-gray-400">Tidak ada pengguna ditemukan</span>
                            @if(request('search') || request('role'))
                                <span class="text-sm text-gray-400">Coba ubah kata kunci pencarian atau filter role</span>
                                <a href="{{ route('users.index') }}" class="mt-2 px-4 py-2 bg-gray-100 text-gray-600 rounded-full text-sm font-bold hover:bg-gray-200 transition-all">Reset Pencarian</a>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-6">
            {{ $users->links() }}
        </div>
    </div>

    <!-- Modal Konfirmasi Hapus -->
    <div id="modal-hapus" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50">
        <div class="bg-white rounded-2xl shadow-xl p-8 max-w-sm w-full mx-4">
            <div class="flex flex-col items-center text-center gap-4">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </div>
                <div>
                    <h3 class="text-lg font-black text-[#03045E]">Hapus Pengguna?</h3>
                    <p class="text-gray-500 text-sm mt-1">Data pengguna <span id="nama-user" class="font-bold text-[#03045E]"></span> akan dihapus permanen dan tidak bisa dikembalikan.</p>
                </div>
                <div class="flex gap-3 w-full mt-2">
                    <button onclick="tutupModal()" class="flex-1 py-3 bg-navy text-white rounded-xl font-bold hover:bg-navy-200 transition-all">
                        Batal
                    </button>
                    <form id="form-hapus" method="POST">
                        @csrf @method('DELETE')
                        <button type="submit" class="flex-1 py-3 bg-red-600 text-white rounded-xl font-bold hover:bg-red-700 transition-all px-6">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function bukaModal(url, nama) {
            document.getElementById('nama-user').textContent = nama;
            document.getElementById('form-hapus').action = url;
            const modal = document.getElementById('modal-hapus');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function tutupModal() {
            const modal = document.getElementById('modal-hapus');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        // Tutup modal kalau klik di luar
        document.getElementById('modal-hapus').addEventListener('click', function(e) {
            if (e.target === this) tutupModal();
        });
    </script>

</x-app-layout>