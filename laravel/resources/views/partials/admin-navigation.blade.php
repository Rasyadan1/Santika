@php
    $dashboardActive = request()->routeIs('admin.dashboard');
    $unitsActive = request()->routeIs('admin.units.*');
    $categoriesActive = request()->routeIs('admin.categories.*');
    $membersActive = request()->routeIs('admin.members.*');
    $returnsActive = request()->routeIs('admin.returns.index');
    $historyActive = request()->routeIs('admin.returns.riwayat', 'admin.returns.print');
    $managementActive = $unitsActive || $categoriesActive || $membersActive;
    $linkClass = 'flex shrink-0 items-center rounded-lg px-3 py-2.5 text-sm transition md:w-full';
    $activeClass = 'border-l-2 border-primary bg-sidebar-accent text-sidebar-accent-foreground';
    $inactiveClass = 'text-muted-foreground hover:bg-sidebar-accent hover:text-sidebar-accent-foreground';
@endphp

<aside @class([
    'flex flex-col border-b border-sidebar-border bg-sidebar md:sticky md:top-0 md:h-screen md:w-64 md:shrink-0 md:border-r md:border-b-0',
    'no-print' => $hideWhenPrinting ?? false,
])>
    <div class="px-6 py-5">
        <span class="font-display text-3xl tracking-wider text-primary">SANTIKA</span>
        <p class="text-xs text-muted-foreground">Panel Juru Kunci (Admin)</p>
    </div>

    <nav aria-label="Navigasi admin" class="flex flex-1 items-start gap-1 overflow-x-auto px-3 pb-3 md:flex-col md:overflow-visible">
        <a href="{{ route('admin.dashboard') }}" @if ($dashboardActive) aria-current="page" @endif class="{{ $linkClass }} {{ $dashboardActive ? $activeClass : $inactiveClass }}">
            Dashboard
        </a>

        <details id="admin-data-menu" class="group shrink-0 md:w-full">
            <summary aria-controls="admin-data-submenu" @if ($managementActive) aria-current="location" @endif class="flex cursor-pointer list-none items-center justify-between gap-3 rounded-lg px-3 py-2.5 text-sm transition marker:content-none {{ $managementActive ? 'text-primary' : $inactiveClass }}">
                <span>Kelola Data</span>
                <svg aria-hidden="true" class="h-4 w-4 shrink-0 transition-transform group-open:rotate-180" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5.22 7.47a.75.75 0 0 1 1.06 0L10 11.19l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 8.53a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                </svg>
            </summary>
            <div id="admin-data-submenu" class="mt-1 flex flex-col gap-1 border-l border-sidebar-border pl-2 md:ml-3">
                <a href="{{ route('admin.units.index') }}" @if ($unitsActive) aria-current="page" @endif class="{{ $linkClass }} {{ $unitsActive ? $activeClass : $inactiveClass }}">
                    Unit
                </a>
                <a href="{{ route('admin.categories.index') }}" @if ($categoriesActive) aria-current="page" @endif class="{{ $linkClass }} {{ $categoriesActive ? $activeClass : $inactiveClass }}">
                    Kategori
                </a>
                <a href="{{ route('admin.members.index') }}" @if ($membersActive) aria-current="page" @endif class="{{ $linkClass }} {{ $membersActive ? $activeClass : $inactiveClass }}">
                    Anggota
                </a>
            </div>
        </details>

        <a href="{{ route('admin.returns.index') }}" @if ($returnsActive) aria-current="page" @endif class="{{ $linkClass }} {{ $returnsActive ? $activeClass : $inactiveClass }}">
            Pengembalian
        </a>
        <a href="{{ route('admin.returns.riwayat') }}" @if ($historyActive) aria-current="page" @endif class="{{ $linkClass }} {{ $historyActive ? $activeClass : $inactiveClass }}">
            Riwayat Peminjaman
        </a>
    </nav>

    <div class="border-t border-sidebar-border p-4">
        <p class="truncate text-sm font-medium">{{ auth()->user()->name }}</p>
        <p class="truncate text-xs text-muted-foreground">{{ auth()->user()->email }}</p>
    </div>
    <form action="{{ route('logout') }}" method="POST" class="border-t border-sidebar-border p-3 md:mt-auto">
        @csrf
        <button type="submit" class="flex w-full items-center rounded-lg px-3 py-2.5 text-left text-sm text-destructive transition hover:bg-destructive/20">
            Keluar
        </button>
    </form>
</aside>

<script>
    (() => {
        const menu = document.getElementById('admin-data-menu');
        const storageKey = 'santika-admin-data-menu-open';

        if (!menu) return;

        try {
            const savedState = sessionStorage.getItem(storageKey);
            if (savedState !== null) menu.open = savedState === 'true';
        } catch {}

        menu.addEventListener('toggle', () => {
            try {
                sessionStorage.setItem(storageKey, String(menu.open));
            } catch {}
        });
    })();
</script>
