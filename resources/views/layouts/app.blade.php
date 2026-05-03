<!DOCTYPE html>
@include('layouts.navigation')
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    
<title>TTrinhPerfume</title>

<!-- Tailwind / Vite assets -->
@php
    $manifestPath = public_path('build/manifest.json');
    $manifest = null;
    if (file_exists($manifestPath)) {
        $manifest = json_decode(file_get_contents($manifestPath), true);
    }
@endphp

@if($manifest)
    {{-- Use prebuilt assets from public/build as a safe fallback --}}
    @if(isset($manifest['resources/css/app.css']['file']))
        <link rel="stylesheet" href="{{ asset('build/' . $manifest['resources/css/app.css']['file']) }}">
    @endif
    @if(isset($manifest['resources/js/app.js']['file']))
        <script type="module" src="{{ asset('build/' . $manifest['resources/js/app.js']['file']) }}" defer></script>
    @endif
@else
    @vite(['resources/css/app.css', 'resources/js/app.js'])
@endif

<!-- Font đẹp hơn -->
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600&family=Inter:wght@300;400;500&display=swap" rel="stylesheet">

</head>

<body class="bg-[#f8f5f2] text-[#0f0f0f] font-[Inter]">



<!-- MAIN CONTENT -->
<main class="px-10 py-8">
    @yield('content')
</main>

<!-- FOOTER -->
<footer class="mt-20 border-t pt-10 pb-6 text-center text-sm text-gray-500">

    <p class="font-[Citadel Script] text-base mb-3">
        TTrinhPerfume
    </p>

    <div class="flex justify-center gap-6 mb-4">
        <span>Privacy Policy</span>
        <span>Terms of Service</span>
        <span>Shipping</span>
        <span>Contact</span>
    </div>

    <p>
        © 2026 TTrinhPerfume. The Art of Olfactory Elegance.
    </p>

</footer>

</body>
</html>
