<x-guest-layout>
    <div class="max-w-md mx-auto w-full">
        <h2 h2 class="text-2xl font-bold text-center mb-2"> Lupa Kata Sandi </h2>

        <p class="text-sm text-gray-600 text-center mb-6"> Jika Anda lupa kata sandi, silakan hubungi Administrator Sekolah untuk melakukan reset kata sandi akun Anda. Silakan isi data akun Anda terlebih dahulu agar Administrator dapat membantu proses verifikasi. </p>

        <div class="space-y-4">
            <div>
                <x-input-label for="nama" :value="'Nama Akun'" />
                <input id="nama" type="text" class="border-gray-300 rounded-md shadow-sm block mt-1 w-full" placeholder="Masukkan nama akun">
                <div style="min-height:24px;">
                    <p id="error-nama" class="text-sm text-red-600 hidden"></p>
                </div>
            </div>
            <div>
                <x-input-label for="email" :value="'Email'" />
                <input id="email" type="email" class="border-gray-300 rounded-md shadow-sm block mt-1 w-full" placeholder="Masukkan email akun">
            </div>
            <div class="grid gap-3 mt-6">
                <button type="button" onclick="hubungiAdmin1()" class="w-full px-4 py-3 rounded-md text-white font-semibold bg-[#03045E] hover:opacity-90"> Hubungi Administrator 1 </button>
                <button type="button" onclick="hubungiAdmin2()" class="w-full px-4 py-3 rounded-md text-white font-semibold bg-[#0077B6] hover:opacity-90"> Hubungi Administrator 2 </button>
                <a href="{{ route('login') }}" class="w-full text-center px-4 py-3 rounded-md bg-gray-100 hover:bg-gray-200 font-semibold"> Kembali ke Login
                </a>
            </div>
        </div>
    </div>

    <script>
        function buildMessage() {
            const nama = document.getElementById('nama').value.trim();
            const email = document.getElementById('email').value.trim();

            return `Halo Admin EduConnect, Saya lupa kata sandi akun EduConnect. Detail Akun saya: 
            Nama: ${nama || '-'} 
            Email: ${email || '-'} 
            Mohon bantuan untuk melakukan reset kata sandi akun saya. Terima kasih.`;
        }

        function hubungiAdmin1() {
            if (!validateForm()) return;
            const nomorAdmin1 = '6282134418171';
            const pesan = encodeURIComponent(buildMessage());
            window.open(
                `https://wa.me/${nomorAdmin1}?text=${pesan}`,
                '_blank'
            );
        }

        function hubungiAdmin2() {
            if (!validateForm()) return;
            const nomorAdmin2 = '62895603282284';
            const pesan = encodeURIComponent(buildMessage());
            window.open(
                `https://wa.me/${nomorAdmin2}?text=${pesan}`,
                '_blank'
            );
        }

        function validateForm() {
            const nama = document.getElementById('nama').value.trim();
            const error = document.getElementById('error-nama');
            error.classList.add('hidden');

            if (!nama) {
                error.textContent =
                    'Kolom nama akun wajib diisi.';
                error.classList.remove('hidden');
                return false;
            }
            return true;
        }
    </script>
</x-guest-layout>