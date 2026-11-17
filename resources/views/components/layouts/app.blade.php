<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Monitoring Honor Mitra' }} · BPS 7309</title>
    {{-- Tailwind via CDN agar tidak perlu build npm. Untuk produksi boleh diganti Vite. --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        [x-cloak] { display: none !important; }
        .tabular { font-variant-numeric: tabular-nums; }
    </style>
    @livewireStyles
</head>
<body class="min-h-screen bg-slate-100 text-slate-800 antialiased">
    <header class="bg-white border-b border-slate-200">
        <div class="max-w-[1500px] mx-auto px-4 sm:px-6 flex items-center gap-6 h-14">
            <a href="{{ route('monitoring') }}" wire:navigate class="flex items-center gap-2 font-semibold text-slate-900">
                <span class="inline-flex h-7 w-7 items-center justify-center rounded-md bg-sky-700 text-white text-xs">MH</span>
                <span>Monitoring Honor Mitra</span>
            </a>
            <nav class="flex items-center gap-1 text-sm">
                @php
                    $menu = [
                        ['monitoring', 'Monitoring', 'monitoring'],
                        ['kegiatan.index', 'Kegiatan', 'kegiatan.*'],
                        ['mitra.index', 'Mitra', 'mitra.*'],
                        ['pengaturan', 'Pengaturan Limit', 'pengaturan'],
                    ];
                @endphp
                @foreach ($menu as [$rute, $label, $pola])
                    <a href="{{ route($rute) }}" wire:navigate
                       class="px-3 py-1.5 rounded-md {{ request()->routeIs($pola) ? 'bg-sky-50 text-sky-800 font-medium' : 'text-slate-600 hover:bg-slate-100' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </nav>
            <div class="ml-auto text-xs text-slate-400 hidden md:block">BPS Kabupaten Pangkajene dan Kepulauan</div>
        </div>
    </header>

    <main class="max-w-[1500px] mx-auto px-4 sm:px-6 py-6">
        {{ $slot }}
    </main>

    @livewireScripts
</body>
</html>
