@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-mystic pb-24">
    <!-- Navigation Bar -->
    <nav class="sticky top-0 z-40 border-b border-border bg-glass backdrop-blur-xl">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3">
            <span class="font-display text-3xl tracking-wider text-primary">SANTIKA</span>
            <div class="flex items-center gap-3">
                <div class="flex items-center gap-2">
                    <span class="flex h-9 w-9 items-center justify-center rounded-full bg-accent text-lg">🧙</span>
                    <span class="hidden text-sm sm:inline">{{ Auth::user()->name ?? 'Mbah Juna' }}</span>
                </div>
                <a href="#" class="rounded-lg border border-destructive/60 px-3 py-1.5 text-sm text-destructive hover:bg-destructive hover:text-destructive-foreground">Logout</a>
            </div>
        </div>
    </nav>

    <!-- Hero Header -->
    <header class="mx-auto max-w-7xl px-4 pt-14 pb-10 text-center">
        <div class="mb-2 inline-block text-5xl animate-float">👻</div>
        <h1 class="font-display text-5xl tracking-wide text-foreground md:text-7xl">
            Katalog <span class="text-primary">Alat</span> <span class="text-destructive">Mistis</span>
        </h1>
        <p class="mt-3 text-muted-foreground">Pinjam seketika, balikin sebelum malam Jumat.</p>
        
        <!-- Form Pencarian Unit -->
        <form class="mx-auto mt-8 flex max-w-xl gap-2 rounded-xl border border-border bg-glass p-2 backdrop-blur-xl" action="/katalog" method="GET">
            <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari unit... (contoh: keris)" class="flex-1 bg-transparent px-3 text-sm outline-none placeholder:text-muted-foreground" />
            <button type="submit" class="rounded-lg bg-primary px-5 py-2 text-sm font-semibold text-primary-foreground">Cari</button>
        </form>
    </header>

    <!-- Content Grid & Sidebar -->
    <div class="mx-auto grid max-w-7xl gap-8 px-4 lg:grid-cols-[1fr_300px]">
        <!-- List Katalog Unit -->
        <section class="grid gap-5 sm:grid-cols-2 xl:grid-cols-3">
            @forelse($units as $unit)
                <article class="group flex flex-col rounded-2xl border border-border bg-card p-5 transition hover:-translate-y-1 hover:border-primary/60 hover:glow-primary">
                    <!-- Icon & Status -->
                    <div class="mb-4 flex items-start justify-between">
                        <span class="text-4xl transition group-hover:scale-110">
                            {{ $unit->emoji ?? '🔮' }}
                        </span>
                        @if(($unit->status ?? 'Tersedia') == 'Tersedia')
                            <span class="rounded-full px-2.5 py-1 text-xs font-semibold bg-success/15 text-success">Tersedia</span>
                        @else
                            <span class="rounded-full px-2.5 py-1 text-xs font-semibold bg-destructive/15 text-destructive">Dipinjam</span>
                        @endif
                    </div>

                    <!-- Detail Unit -->
                    <p class="font-mono text-xs text-muted-foreground">{{ $unit->kode_unit }}</p>
                    <h3 class="mt-1 text-lg font-semibold">{{ $unit->nama_unit }}</h3>
                    <p class="mt-1 flex-1 text-sm text-muted-foreground">
                        {{ $unit->deskripsi ?? 'Perlengkapan gaib berkualitas tinggi, dijamin mempan dan aman.' }}
                    </p>

                    <!-- Badges Kategori -->
                    <div class="mt-3 flex flex-wrap gap-1.5">
                        @if(isset($unit->categories) && count($unit->categories) > 0)
                            @foreach($unit->categories as $cat)
                                <span class="rounded-md bg-accent px-2 py-0.5 text-xs text-accent-foreground">{{ $cat->nama_kategori }}</span>
                            @endforeach
                        @else
                            <span class="rounded-md bg-accent px-2 py-0.5 text-xs text-accent-foreground">Mistis</span>
                        @endif
                    </div>

                    <!-- Tombol Aksi -->
                    @if(($unit->status ?? 'Tersedia') == 'Tersedia')
                        <form action="#" method="POST" class="mt-5">
                            @csrf
                            <button type="submit" class="w-full rounded-lg bg-primary py-2.5 text-sm font-semibold text-primary-foreground transition hover:brightness-110">
                                Pinjam Unit
                            </button>
                        </form>
                    @else
                        <button disabled class="mt-5 w-full rounded-lg bg-muted py-2.5 text-sm font-semibold text-muted-foreground cursor-not-allowed">
                            Sedang Dipinjam
                        </button>
                    @endif
                </article>
            @empty
                <div class="col-span-full rounded-2xl border border-dashed border-border p-8 text-center text-muted-foreground">
                    👻 Belum ada unit alat gaib yang tersedia saat ini.
                </div>
            @endforelse
        </section>

        <!-- Sidebar Pinjaman Aktif -->
        <aside class="h-fit rounded-2xl border border-primary/40 bg-glass p-5 backdrop-blur-xl lg:sticky lg:top-20">
            <h2 class="font-semibold">Pinjaman Aktif Anda <span class="text-muted-foreground">(Maksimal 2)</span></h2>
            <div class="mt-3 h-2 overflow-hidden rounded-full bg-muted">
                <div class="h-full w-1/2 rounded-full bg-primary"></div>
            </div>
            <p class="mt-1 text-xs text-muted-foreground">1 dari 2 slot terpakai</p>
            
            <!-- Item Aktif Dummy -->
            <div class="mt-4 flex items-center gap-3 rounded-xl bg-card p-3">
                <span class="text-2xl">🧴</span>
                <div class="text-sm">
                    <p class="font-medium">Minyak Pelet Jaran Goyang</p>
                    <p class="text-xs text-destructive">Kembali: Kamis, 29 Okt (sebelum magrib)</p>
                </div>
            </div>

            <div class="mt-3 rounded-xl border border-dashed border-border p-3 text-center text-xs text-muted-foreground">
                Slot kosong — arwah menunggu
            </div>
        </aside>
    </div>
</div>
@endsection