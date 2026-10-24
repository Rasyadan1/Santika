@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-mystic pb-12">
    @include('partials.member-navigation')

    <header class="mx-auto max-w-7xl px-4 pb-10 pt-14 text-center">
        <div class="mb-2 inline-block animate-float text-5xl">👻</div>
        <h1 class="font-display text-5xl tracking-wide text-foreground md:text-7xl">
            Katalog <span class="text-primary">Alat</span> <span class="text-destructive">Mistis</span>
        </h1>
        <p class="mt-3 text-muted-foreground">Pinjam seketika, balikin sebelum malam Jumat.</p>

        <form class="mx-auto mt-8 flex max-w-xl gap-2 rounded-xl border border-border bg-glass p-2 backdrop-blur-xl" action="{{ route('katalog') }}" method="GET">
            <input type="search" name="search" value="{{ $search }}" placeholder="Cari unit... (contoh: keris)" class="flex-1 bg-transparent px-3 text-sm outline-none placeholder:text-muted-foreground" />
            <button type="submit" class="rounded-lg bg-primary px-5 py-2 text-sm font-semibold text-primary-foreground transition hover:brightness-110">Cari</button>
        </form>
    </header>

    <main class="mx-auto max-w-7xl px-4">
        @if (session('success'))
            <div class="mb-5 rounded-lg border border-success/50 bg-success/10 p-4 text-sm text-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="mb-5 rounded-lg border border-destructive/50 bg-destructive/10 p-4 text-sm text-destructive">{{ session('error') }}</div>
        @endif

        <div class="grid gap-8 lg:grid-cols-[1fr_300px]">
            <section>
                <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-3">
                    @forelse ($units as $unit)
                        <article class="group flex flex-col rounded-2xl border border-border bg-card p-5 transition hover:-translate-y-1 hover:border-primary/60 hover:glow-primary">
                            <div class="mb-4 flex items-start justify-between">
                                <span class="text-4xl transition group-hover:scale-110">🔮</span>
                                @if ($unit->status === 'tersedia')
                                    <span class="rounded-full bg-success/15 px-2.5 py-1 text-xs font-semibold text-success">Tersedia</span>
                                @else
                                    <span class="rounded-full bg-destructive/15 px-2.5 py-1 text-xs font-semibold text-destructive">Dipinjam</span>
                                @endif
                            </div>

                            <p class="font-mono text-xs text-muted-foreground">{{ $unit->kode_unit }}</p>
                            <h2 class="mt-1 text-lg font-semibold">{{ $unit->nama_unit }}</h2>
                            <p class="mt-1 flex-1 text-sm text-muted-foreground">{{ $unit->deskripsi ?: 'Perlengkapan gaib berkualitas tinggi.' }}</p>

                            <div class="mt-3 flex flex-wrap gap-1.5">
                                @forelse ($unit->categories as $category)
                                    <span class="rounded-md bg-accent px-2 py-0.5 text-xs text-accent-foreground">{{ $category->nama_kategori }}</span>
                                @empty
                                    <span class="rounded-md bg-accent px-2 py-0.5 text-xs text-accent-foreground">Mistis</span>
                                @endforelse
                            </div>

                            @if ($unit->status === 'tersedia')
                                <form action="{{ route('loans.store', $unit) }}" method="POST" class="mt-5">
                                    @csrf
                                    <button type="submit" class="w-full rounded-lg bg-primary py-2.5 text-sm font-semibold text-primary-foreground transition hover:brightness-110">
                                        Pinjam Unit
                                    </button>
                                </form>
                            @else
                                <button type="button" disabled class="mt-5 w-full cursor-not-allowed rounded-lg bg-muted py-2.5 text-sm font-semibold text-muted-foreground">
                                    Sedang Dipinjam
                                </button>
                            @endif
                        </article>
                    @empty
                        <div class="col-span-full rounded-2xl border border-dashed border-border p-8 text-center text-muted-foreground">
                            👻 {{ $search ? 'Tidak ada unit yang cocok dengan pencarian.' : 'Belum ada unit alat gaib yang tersedia saat ini.' }}
                        </div>
                    @endforelse
                </div>
                <div class="mt-6">{{ $units->links() }}</div>
            </section>

            <aside class="h-fit rounded-2xl border border-primary/40 bg-glass p-5 backdrop-blur-xl lg:sticky lg:top-20">
                <h2 class="font-semibold">Pinjaman Aktif Anda <span class="text-muted-foreground">({{ $activeLoans->count() }}/{{ config('santika.batas_pinjam', 2) }})</span></h2>
                <div class="mt-3 h-2 overflow-hidden rounded-full bg-muted">
                    <div class="h-full rounded-full bg-primary" style="width: {{ min(100, $activeLoans->count() / max(1, config('santika.batas_pinjam', 2)) * 100) }}%"></div>
                </div>
                <div class="mt-4 space-y-3">
                    @forelse ($activeLoans as $loan)
                        <div class="rounded-xl bg-card p-3">
                            <p class="text-sm font-medium">{{ $loan->unit->nama_unit }}</p>
                            <p class="mt-1 text-xs text-muted-foreground">Jatuh tempo: {{ $loan->tanggal_jatuh_tempo->format('d M Y') }}</p>
                        </div>
                    @empty
                        <p class="rounded-xl border border-dashed border-border p-3 text-center text-xs text-muted-foreground">Belum ada pinjaman aktif.</p>
                    @endforelse
                </div>
            </aside>
        </div>
    </main>
</div>
@endsection
