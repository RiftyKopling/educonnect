<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div style="min-height: 60px;">
        @if ($errors->has('email') && str_contains($errors->first('email'), 'credentials_failed'))
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-md flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M12 3a9 9 0 100 18A9 9 0 0012 3z" />
                </svg>
                <span>Email atau kata sandi yang kamu masukkan salah.</span>
            </div>
        @endif
    </div>

    <form method="POST" action="{{ route('login') }}" novalidate>
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <input id="email"
                type="email"
                name="email"
                value="{{ old('email') }}"
                required
                autofocus
                autocomplete="username"
                style="outline: none; background-color: white;"
                class="border-gray-300 rounded-md shadow-sm block mt-1 w-full"
                onfocus="this.style.borderColor='#111827'; this.style.boxShadow='0 0 0 1px #111827'; this.style.backgroundColor='white';"
                onblur="this.style.borderColor=''; this.style.boxShadow=''; this.style.backgroundColor='';">
                <div style="min-height: 24px;">
                    @if($errors->has('email') && !str_contains($errors->first('email'), 'credentials_failed'))
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    @endif
                    <p id="error-email" class="text-sm text-red-600 mt-2 hidden"></p>
                </div>
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Kata Sandi')" />

            <div class="flex mt-1">
                <input id="password"
                    type="password"
                    name="password"
                    required
                    autocomplete="current-password"
                    class="border-gray-300 rounded-l-md shadow-sm block w-full"
                    style="outline: none;"
                    onfocus="this.style.borderColor='#111827'; this.style.boxShadow='0 0 0 1px #111827'; this.style.backgroundColor='white';"
                    onblur="this.style.borderColor=''; this.style.boxShadow=''; this.style.backgroundColor='';">

                <button type="button" id="toggle-btn"
                        onclick="togglePassword()"
                        style="background-color: #111827; color: white; border-color: #111827;"
                        class="flex items-center justify-center px-3 border border-l-0 rounded-r-md">
                        
                    <svg id="eye-icon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7
                            -1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <svg id="eye-off-icon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7
                            a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243
                            M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29
                            m7.532 7.532l3.29 3.29M3 3l3.59 3.59
                            m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7
                            a10.025 10.025 0 01-4.132 4.411m0 0L21 21" />
                    </svg>
                </button>
            </div>
            <div style="min-height: 24px;">
                 <p id="error-password" class="text-sm text-red-600 mt-2 hidden"></p>
            </div>
        </div>

        <div class="flex items-center justify-between mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox"
                    class="rounded border-gray-300 text-gray-900 shadow-sm"
                    style="--tw-ring-color: #111827;"
                    name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Ingat Pengguna') }}</span>
            </label>

            <div class="flex items-center gap-3">
                @if (Route::has('password.request'))
                    <a style="font-size: 0.875rem; color: #4b5563;"
                    onmouseover="this.style.color='#dc6300'"
                    onmouseout="this.style.color='#4b5563'"
                    class="rounded-md focus:outline-none"
                    href="{{ route('password.request') }}">
                        {{ __('Lupa kata sandi?') }}
                    </a>
                @endif

                <x-primary-button>
                    {{ __('MASUK') }}
                </x-primary-button>
            </div>
        </div>

        <script>
            function togglePassword() {
                const input = document.getElementById('password');
                const eyeIcon = document.getElementById('eye-icon');
                const eyeOffIcon = document.getElementById('eye-off-icon');
                const btn = document.getElementById('toggle-btn');

                if (input.type === 'password') {
                    input.type = 'text';
                    eyeIcon.classList.add('hidden');
                    eyeOffIcon.classList.remove('hidden');
                    btn.style.backgroundColor = 'white';
                    btn.style.color = '#111827';
                    btn.style.borderColor = '#d1d5db';
                } else {
                    input.type = 'password';
                    eyeIcon.classList.remove('hidden');
                    eyeOffIcon.classList.add('hidden');
                    btn.style.backgroundColor = '#111827';
                    btn.style.color = 'white';
                    btn.style.borderColor = '#111827';
                }
            }

            document.querySelector('form').addEventListener('submit', function(e) {
                const email = document.getElementById('email').value.trim();
                const password = document.getElementById('password').value.trim();

                if (!email && !password) {
                    e.preventDefault();
                    showError('email', 'Kolom email wajib diisi.');
                    showError('password', 'Kolom kata sandi wajib diisi.');
                    return;
                }

                if (!email) {
                    e.preventDefault();
                    showError('email', 'Kolom email wajib diisi.');
                    return;
                }

                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(email)) {
                    e.preventDefault();
                    showError('email', 'Kolom email harus berupa alamat email yang valid.');
                    return;
                }

                if (!password) {
                    e.preventDefault();
                    showError('password', 'Kolom kata sandi wajib diisi.');
                    return;
                }
            });

            function showError(fieldId, message) {
                const error = document.getElementById('error-' + fieldId);
                error.textContent = message;
                error.classList.remove('hidden');
            }

            ['email', 'password'].forEach(function(id) {
                document.getElementById(id).addEventListener('input', function() {
                    const error = document.getElementById('error-' + id);
                    error.classList.add('hidden');
                });
            });
        </script>
    </form>
</x-guest-layout>