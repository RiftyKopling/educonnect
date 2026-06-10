<header class="bg-gradient-to-r from-[#0077B6] to-[#03045E] text-white p-4 shadow-md flex justify-between items-center px-8 relative">
    <div class="flex items-center gap-4">
        <button @click="sidebarOpen = !sidebarOpen" class="p-2 bg-white/20 hover:bg-white/30 rounded-lg shadow-sm transition-all focus:outline-none hover:scale-105 active:scale-95">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>
        <h1 class="text-2xl font-bold tracking-tight italic">EduConnect</h1>
    </div>

    <div class="relative" x-data="{ open: false }">
        <button @click="open = !open" @click.away="open = false" class="flex items-center gap-4 focus:outline-none hover:opacity-90 transition-opacity">
            <div class="text-right">
                <p class="text-sm font-medium leading-none">{{ Auth::user()->name }}</p>
                <p class="text-[10px] text-white/70 uppercase tracking-widest mt-1">{{ Auth::user()->role?->name }}</p>
            </div>
            <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-[#03045E] shadow-inner">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
            </div>
        </button>

        <div x-show="open" 
             x-transition:enter="transition ease-out duration-100"
             x-transition:enter-start="transform opacity-0 scale-95"
             x-transition:enter-end="transform opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-75"
             x-transition:leave-start="transform opacity-100 scale-100"
             x-transition:leave-end="transform opacity-0 scale-95"
             class="absolute right-0 mt-2 w-48 bg-white rounded-2xl shadow-xl border border-gray-100 py-2 z-50 text-gray-800"
             style="display: none;">
            
            <div class="px-4 py-1.5 border-b border-gray-50 mb-1">
                <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Aksi Pengguna</span>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-2 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 font-semibold transition-colors text-left">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Keluar Sistem
                </button>
            </form>
        </div>
    </div>
</header>