<x-app-layout>
    <div class="max-w-3xl mx-auto">
        <div class="mb-8">
            <a href="{{ route('users.index') }}" class="text-[#03045E] font-bold flex items-center gap-2 mb-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Daftar
            </a>
            <h2 class="text-3xl font-black text-[#03045E] tracking-tight">Edit Pengguna: {{ $user->name }}</h2>
        </div>

        <div class="bg-white rounded-[2rem] shadow-sm p-8 border border-gray-100">
            <!-- Action mengarah ke method update, tambahkan @method('PUT') -->
            <form action="{{ route('users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <!-- Nama -->
                    <div>
                        <label class="block text-sm font-bold text-[#03045E] mb-2 uppercase tracking-widest">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full rounded-2xl border-gray-200 focus:ring-[#03045E] focus:border-[#03045E] p-4">
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-bold text-[#03045E] mb-2 uppercase tracking-widest">Alamat Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full rounded-2xl border-gray-200 focus:ring-[#03045E] focus:border-[#03045E] p-4">
                        @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Role -->
                    <div>
                        <label class="block text-sm font-bold text-[#03045E] mb-2 uppercase tracking-widest">Peran (Role)</label>
                        <select name="role_id" class="w-full rounded-2xl border-gray-200 focus:ring-[#03045E] focus:border-[#03045E] p-4">
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ (old('role_id', $user->role_id) == $role->id) ? 'selected' : '' }}>
                                    {{ strtoupper(str_replace('-', ' ', $role->name)) }}
                                </option>
                            @endforeach
                        </select>
                        @error('role_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="bg-blue-50 p-6 rounded-2xl border border-blue-100">
                        <h4 class="text-[#03045E] font-bold mb-2">Ganti Password (Opsional)</h4>
                        <p class="text-sm text-blue-600 mb-4 font-medium">Kosongkan kolom di bawah jika Anda tidak ingin mengubah password pengguna.</p>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <!-- Password -->
                            <div>
                                <label class="block text-sm font-bold text-[#03045E] mb-2 uppercase tracking-widest">Password Baru</label>
                                <input type="password" name="password" class="w-full rounded-2xl border-white focus:ring-[#03045E] focus:border-[#03045E] p-4" placeholder="********">
                                @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <!-- Konfirmasi Password -->
                            <div>
                                <label class="block text-sm font-bold text-[#03045E] mb-2 uppercase tracking-widest">Konfirmasi Password Baru</label>
                                <input type="password" name="password_confirmation" class="w-full rounded-2xl border-white focus:ring-[#03045E] focus:border-[#03045E] p-4" placeholder="********">
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="w-full py-4 bg-amber-500 text-white rounded-2xl font-bold shadow-lg hover:bg-amber-600 transition-all uppercase tracking-widest">
                        Perbarui Data Pengguna
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
