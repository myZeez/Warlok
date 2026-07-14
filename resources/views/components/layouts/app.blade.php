<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ isset($title) ? $title.' — Warlok' : 'Warlok — Warung Lokal Dekat Kamu' }}</title>
    <meta name="description" content="Warlok — temukan UMKM dan produk lokal di sekitar kamu, pesan langsung lewat WhatsApp.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen overflow-x-hidden bg-paper font-sans text-neutral-900 antialiased">
    <a href="#main-content" class="sr-only focus:not-sr-only focus:fixed focus:top-2 focus:left-2 focus:z-50 focus:rounded-lg focus:bg-white focus:px-4 focus:py-2 focus:shadow-lg">
        Langsung ke konten
    </a>

    <main id="main-content" class="pb-20">
        {{ $slot }}
    </main>

    <x-bottom-nav />

    @livewireScripts
</body>
</html>
