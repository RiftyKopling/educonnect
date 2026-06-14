<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduConnect - {{ $title ?? 'Dashboard' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #F5F5F5; }
        .bg-navy { background-color: #03045E; }
        .text-navy { color: #03045E; }
        .rounded-capsule { border-radius: 9999px; }
        .shadow-soft { box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05); }
    </style>
</head>
<body class="flex min-h-screen text-gray-800 antialiased" x-data="{ sidebarOpen: false }">

    <x-sidebar />

    <div class="flex-1 flex flex-col min-w-0 transition-all duration-300">
        <x-header />

        <main class="p-6 overflow-y-auto">
            {{ $slot }}
        </main>
    </div>

</body>
</html>