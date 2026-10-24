@php
    $items = [
        ['label' => 'Katalog', 'route' => 'katalog', 'active' => request()->routeIs('katalog')],
        ['label' => 'Pinjaman', 'route' => 'pinjaman.index', 'active' => request()->routeIs('pinjaman.index')],
        ['label' => 'Riwayat', 'route' => 'pinjaman.riwayat', 'active' => request()->routeIs('pinjaman.riwayat')],
        ['label' => 'Profil', 'route' => 'profile.show', 'active' => request()->routeIs('profile.*')],
    ];
@endphp

<nav class="sticky top-0 z-40 border-b border-border bg-glass backdrop-blur-xl" aria-label="Navigasi anggota">
    <div class="mx-auto flex max-w-7xl items-center gap-3 px-4 py-3">
        <a href="{{ route('katalog') }}" class="shrink-0 font-display text-2xl tracking-wider text-primary sm:text-3xl">SANTIKA</a>

        <div class="min-w-0 flex-1 overflow-x-auto">
            <div class="flex min-w-max items-center gap-1 px-1">
                @foreach ($items as $item)
                    <a href="{{ route($item['route']) }}"
                        @if ($item['active']) aria-current="page" @endif
                        class="whitespace-nowrap rounded-lg border px-3 py-2 text-sm font-medium transition sm:px-4 {{ $item['active'] ? 'border-primary/50 bg-primary/20 text-primary shadow-[0_0_18px_-8px_var(--primary)]' : 'border-transparent text-muted-foreground hover:border-border hover:bg-muted hover:text-foreground' }}">
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </div>
        </div>

        <div class="flex shrink-0 items-center gap-2">
            <span class="hidden max-w-36 truncate text-sm text-muted-foreground xl:block">{{ auth()->user()->name }}</span>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="rounded-lg border border-destructive/60 px-3 py-2 text-sm text-destructive transition hover:bg-destructive hover:text-destructive-foreground">Keluar</button>
            </form>
        </div>
    </div>
</nav>
