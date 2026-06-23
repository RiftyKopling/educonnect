@props(['message' => ''])

<div id="error-modal-container" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
    <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full mx-4 transform transition-all duration-300 scale-100">
        <div class="flex flex-col items-center text-center gap-4">
            <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center animate-pulse">
                <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            
            <div>
                <h3 class="text-2xl font-black text-[#03045E]">Akses Ditolak</h3>
                <p class="text-gray-600 text-sm mt-2 leading-relaxed">
                    {{ $message }}
                </p>
            </div>

            <div class="w-full bg-gray-50 rounded-xl p-4 text-left text-sm text-gray-600">
                <div class="flex items-start gap-2">
                    <svg class="w-5 h-5 text-[#03045E] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <p class="font-medium text-[#03045E]">Informasi:</p>
                        <p class="text-xs">Hanya pembuat pengumuman atau Kepala Sekolah yang dapat mengelola pengumuman ini.</p>
                    </div>
                </div>
            </div>

            <button onclick="tutupErrorModal()" 
                    class="w-full py-3 bg-[#03045E] text-white rounded-xl font-bold hover:bg-[#05086b] transition-all transform hover:scale-[1.02] shadow-lg">
                Kembali ke Manajemen Pengumuman
            </button>
        </div>
    </div>
</div>

<script>
    function tutupErrorModal() {
        const modal = document.getElementById('error-modal-container');
        if (modal) {
            modal.style.transition = 'opacity 0.3s ease';
            modal.style.opacity = '0';
            setTimeout(() => {
                modal.style.display = 'none';
                // Redirect ke index
                window.location.href = "{{ route('pengumuman.index') }}";
            }, 300);
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('error-modal-container');
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    tutupErrorModal();
                }
            });
        }
    });

    setTimeout(function() {
        const modal = document.getElementById('error-modal-container');
        if (modal) {
            tutupErrorModal();
        }
    }, 10000);
</script>