<x-app-layout>
    <div class="max-w-3xl mx-auto">
        <div class="mb-8">
            <div class="flex items-center gap-2 text-sm text-gray-500 mb-2">
                <a href="{{ route('dashboard') }}" class="hover:text-[#03045E] font-medium">
                    Dashboard
                </a>
                <span>›</span>
                <a href="{{ route('users.index') }}" class="hover:text-[#03045E] font-medium">Manajemen Pengguna</a>
                <span>›</span>
                <span class="text-[#03045E] font-bold">Edit Pengguna</span>
            </div>
            <a href="{{ route('users.index') }}" class="flex items-center gap-2 text-sm text-gray-500 hover:text-[#03045E] font-medium mb-4">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali
            </a>
            <h2 class="text-3xl font-black text-[#03045E] tracking-tight">Edit Pengguna: {{ $user->name }}</h2>
            <p class="text-gray-500  text-sm mt-1">Edit data akun user EduConnect.</p>
        </div>

        <div class="bg-white rounded-[2rem] shadow-sm p-8 border border-gray-100">
            <!-- Action mengarah ke method update, tambahkan @method('PUT') -->
            <form action="{{ route('users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')
                @if(session('error'))
                    <div id="notif-error" class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-2xl flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M12 3a9 9 0 100 18A9 9 0 0012 3z"/></svg>
                            <span class="font-medium">{{ session('error') }}</span>
                        </div>
                        <button onclick="tutupNotifError()" class="text-red-700 hover:text-red-900 ml-4">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border border-red-400 text-red-700 rounded-2xl">
                        <div class="flex items-center gap-2 mb-2">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M12 3a9 9 0 100 18A9 9 0 0012 3z"/></svg>
                            <span class="font-bold">Ada {{ $errors->count() }} kesalahan:</span>
                        </div>
                        <ul class="list-disc list-inside space-y-1 text-sm">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

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
                        <label class="block text-sm font-bold text-[#03045E] mb-2 uppercase tracking-widest">Peran / Role </label>
                        <select name="role_id" class="w-full rounded-2xl border-gray-200 focus:ring-[#03045E] focus:border-[#03045E] p-4 {{ $user->id === auth()->id() ? 'opacity-50 cursor-not-allowed' : '' }}"
                            {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ (old('role_id', $user->role_id) == $role->id) ? 'selected' : '' }}>
                                    {{ strtoupper(str_replace('-', ' ', $role->name)) }}
                                </option>
                            @endforeach
                        </select>
                        @if($user->id === auth()->id())
                            <input type="hidden" name="role_id" value="{{ $user->role_id }}">
                        @endif
                        @if($user->id === auth()->id())
                            <p class="text-amber-500 text-xs mt-1">⚠ Anda tidak dapat mengubah role akun Anda sendiri.</p>
                        @endif
                        @error('role_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="bg-gray-50 p-6 rounded-2xl border border-gray-200">
                        <h4 class="text-[#03045E] font-bold mb-2">GANTI PASSWORD (OPSIONAL)</h4>
                        <p class="text-sm text-gray-500 mb-4 font-medium">Kosongkan kolom di bawah jika Anda tidak ingin mengubah password pengguna.</p>
                        
                        <div class="grid grid-cols-2 gap-4 items-end">
                            <!-- Password -->
                            <div>
                                <label class="block text-sm font-bold text-[#03045E] mb-2 uppercase tracking-widest">kata sandi Baru</label>
                                <div class="flex">
                                    <input type="password" id="password" name="password"
                                        class="w-full rounded-l-2xl border-blue-200 focus:ring-[#03045E] focus:border-[#03045E] p-4"
                                        placeholder="Password baru (kosongkan jika tidak ingin mengubah)">
                                    <button type="button" id="toggle-password"
                                        onclick="togglePass('password', 'eye-icon-1', 'eye-off-icon-1', 'toggle-password')"
                                        style="background-color: #03045E; color: white; border-color: #03045E;"
                                        class="flex items-center justify-center px-3 border border-l-0 rounded-r-2xl">
                                        <svg id="eye-icon-1" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <svg id="eye-off-icon-1" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 4.411m0 0L21 21" />
                                        </svg>
                                    </button>
                                </div>
                                @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <!-- Konfirmasi Password -->
                            <div>
                                <label class="block text-sm font-bold text-[#03045E] mb-2 uppercase tracking-widest">Konfirmasi kata sandi Baru</label>
                                <div class="flex">
                                    <input type="password" id="password_confirmation" name="password_confirmation"
                                        class="w-full rounded-l-2xl border-blue-200 focus:ring-[#03045E] focus:border-[#03045E] p-4"
                                        placeholder="Ulangi password baru">
                                    <button type="button" id="toggle-password-confirm"
                                        onclick="togglePass('password_confirmation', 'eye-icon-2', 'eye-off-icon-2', 'toggle-password-confirm')"
                                        style="background-color: #03045E; color: white; border-color: #03045E;"
                                        class="flex items-center justify-center px-3 border border-l-0 rounded-r-2xl">
                                        <svg id="eye-icon-2" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <svg id="eye-off-icon-2" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 4.411m0 0L21 21" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="w-full py-4 bg-[#03045E] text-white rounded-2xl font-bold shadow-lg hover:bg-blue-900 transition-all uppercase tracking-widest">Perbarui Data Pengguna</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        function togglePass(inputId, eyeId, eyeOffId, btnId) {
            const input = document.getElementById(inputId);
            const eye = document.getElementById(eyeId);
            const eyeOff = document.getElementById(eyeOffId);
            const btn = document.getElementById(btnId);

            if (input.type === 'password') {
                input.type = 'text';
                eye.classList.add('hidden');
                eyeOff.classList.remove('hidden');
                btn.style.backgroundColor = 'white';
                btn.style.color = '#03045E';
                btn.style.borderColor = '#d1d5db';
            } else {
                input.type = 'password';
                eye.classList.remove('hidden');
                eyeOff.classList.add('hidden');
                btn.style.backgroundColor = '#03045E';
                btn.style.color = 'white';
                btn.style.borderColor = '#03045E';
            }
        }
    </script>
</x-app-layout>
