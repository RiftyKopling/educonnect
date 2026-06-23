<x-app-layout>
    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-4">
        <a href="{{ route('dashboard') }}" class="hover:text-[#03045E] font-medium flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Dashboard
        </a>
        <span>›</span>
        <a href="{{ route('mapel.index') }}" class="hover:text-[#03045E] font-medium">Manajemen Mata Pelajaran</a>
        <span>›</span>
        <span class="text-[#03045E] font-bold">Assign Guru</span>
    </div>

    <!-- Header -->
    <div class="mb-6">
        <h2 class="text-3xl font-black text-[#03045E] tracking-tight uppercase">Penugasan Guru</h2>
        <p class="text-gray-500">Mata Pelajaran: <span class="font-bold text-[#03045E]">{{ $mapel->nama_mapel }} ({{ $mapel->kode_mapel }})</span></p>
    </div>

    <div class="bg-white rounded-[2rem] shadow-sm p-8 border border-gray-100">
        <form action="{{ route('mapel.storeAssign', $mapel->id) }}" method="POST">
            @csrf
            <h3 class="text-xl font-bold text-[#03045E] mb-6 border-b pb-2">Pilih Guru Pengampu</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
                @forelse($gurus as $guru)
                    <label class="flex items-center p-4 border border-gray-200 rounded-2xl cursor-pointer hover:bg-gray-50 transition-all {{ $mapel->gurus->contains($guru->id) ? 'bg-[#03045E]/5 border-[#03045E]' : '' }}">
                        <input type="checkbox" name="guru_ids[]" value="{{ $guru->id }}" class="w-5 h-5 text-[#03045E] rounded border-gray-300 focus:ring-[#03045E]" {{ $mapel->gurus->contains($guru->id) ? 'checked' : '' }}>
                        <div class="ml-3">
                            <p class="font-bold text-[#03045E]">{{ $guru->name }}</p>
                            <p class="text-sm text-gray-500">{{ $guru->email }}</p>
                        </div>
                    </label>
                @empty
                    <div class="col-span-full p-4 bg-yellow-50 text-yellow-600 rounded-2xl text-center font-medium">
                        Belum ada user dengan role Guru Mata Pelajaran di sistem.
                    </div>
                @endforelse
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('mapel.index') }}" class="px-8 py-3 bg-gray-100 text-gray-600 rounded-full font-bold hover:bg-gray-200 transition-all">BATAL</a>
                <button type="submit" class="px-8 py-3 bg-[#03045E] text-white rounded-full font-bold shadow-lg hover:scale-105 transition-all">SIMPAN PENUGASAN</button>
            </div>
        </form>
    </div>
</x-app-layout>
