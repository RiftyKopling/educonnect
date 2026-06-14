<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'EduConnect') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased bg-gray-100 min-h-screen flex items-center justify-center px-6 py-8">

        <div class="w-full max-w-4xl bg-white shadow-md rounded-xl overflow-hidden flex" style="height: 550px;">

            <!-- SISI KIRI: SLIDESHOW -->
            <div class="hidden lg:block w-2/5 relative">
                <div id="slideshow" class="absolute inset-0">
                    <div class="slide absolute inset-0 transition-opacity duration-1000 opacity-100">
                        <img src="{{ asset('images/slides/slide1.png') }}" class="w-full h-full object-cover">
                    </div>
                    <div class="slide absolute inset-0 transition-opacity duration-1000 opacity-0">
                        <img src="{{ asset('images/slides/slide2.png') }}" class="w-full h-full object-cover">
                    </div>
                    <div class="slide absolute inset-0 transition-opacity duration-1000 opacity-0">
                        <img src="{{ asset('images/slides/slide3.png') }}" class="w-full h-full object-cover">
                    </div>
                </div>
                <!-- Dots -->
                <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex gap-2 z-10">
                    <div class="dot w-2 h-2 rounded-full bg-white cursor-pointer opacity-100" onclick="goToSlide(0)"></div>
                    <div class="dot w-2 h-2 rounded-full bg-white cursor-pointer opacity-50" onclick="goToSlide(1)"></div>
                    <div class="dot w-2 h-2 rounded-full bg-white cursor-pointer opacity-50" onclick="goToSlide(2)"></div>
                </div>
            </div>

            <!-- SISI KANAN: FORM LOGIN -->
            <div class="w-full lg:w-3/5 flex flex-col px-10 py-8 overflow-y-auto" style="height: 550px;">
                <!-- Logo - pakem di atas -->
                <div class="flex justify-center items-center gap-6 mb-3">
                    <img src="{{ asset('images/fix_logo_educonnect.png') }}" alt="Logo EduConnect" style="width: 130px;">
                    <div style="width: 1px; height: 60px; background-color: #d1d5db;"></div>
                    <img src="{{ asset('images/fix_logo_smpn2mungkid.png') }}" alt="Logo Sekolah" style="width: 130px;">
                </div>

                <!-- Form - di bawah logo -->
                <div class="flex flex-col justify-start flex-1">
                    {{ $slot }}
                </div>
            </div>
        </div>

        <script>
            let currentSlide = 0;
            const slides = document.querySelectorAll('.slide');
            const dots = document.querySelectorAll('.dot');

            function goToSlide(index) {
                slides[currentSlide].style.opacity = '0';
                dots[currentSlide].style.opacity = '0.5';
                currentSlide = index;
                slides[currentSlide].style.opacity = '1';
                dots[currentSlide].style.opacity = '1';
            }

            function nextSlide() {
                const next = (currentSlide + 1) % slides.length;
                goToSlide(next);
            }

            setInterval(nextSlide, 5000);
        </script>
    </body>
</html>
