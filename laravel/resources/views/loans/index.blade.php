@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-mystic pb-12">
    @include('partials.member-navigation')

    <main class="mx-auto max-w-6xl px-4 py-10">
        <header class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-sm text-muted-foreground">Pantau barang yang sedang Anda pinjam</p>
                <h1 class="mt-1 font-display text-4xl tracking-wide text-primary">Pinjaman Aktif</h1>
            </div>
            <a href="{{ route('pinjaman.riwayat') }}" class="text-sm font-medium text-primary transition hover:underline">Lihat seluruh riwayat →</a>
        </header>

        @if (session('success'))
            <div class="mb-5 rounded-lg border border-success/50 bg-success/10 p-4 text-sm text-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="mb-5 rounded-lg border border-destructive/50 bg-destructive/10 p-4 text-sm text-destructive">{{ session('error') }}</div>
        @endif

        <div class="grid gap-5 md:grid-cols-2">
            @forelse ($pinjamanAktif as $pinjaman)
                @php($terlambat = $pinjaman->isTerlambat())
                <article class="rounded-2xl border border-border bg-glass p-6 backdrop-blur-xl transition hover:border-primary/50">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex min-w-0 items-start gap-3">
                            <span class="text-3xl">🔮</span>
                            <div class="min-w-0">
                                <h2 class="truncate text-lg font-semibold">{{ $pinjaman->unit?->nama_unit ?? 'Unit tidak tersedia' }}</h2>
                                @if ($pinjaman->unit?->kode_unit)
                                    <p class="mt-1 font-mono text-xs text-muted-foreground">{{ $pinjaman->unit->kode_unit }}</p>
                                @endif
                            </div>
                        </div>
                        @if ($terlambat)
                            <span class="shrink-0 rounded-full bg-destructive/15 px-3 py-1 text-xs font-semibold text-destructive">Terlambat</span>
                        @else
                            <span class="shrink-0 rounded-full bg-primary/15 px-3 py-1 text-xs font-semibold text-primary">Dipinjam</span>
                        @endif
                    </div>

                    @if ($pinjaman->unit?->categories?->isNotEmpty())
                        <div class="mt-4 flex flex-wrap gap-1.5">
                            @foreach ($pinjaman->unit->categories as $category)
                                <span class="rounded-md bg-accent px-2 py-0.5 text-xs text-accent-foreground">{{ $category->nama_kategori }}</span>
                            @endforeach
                        </div>
                    @endif

                    <dl class="mt-5 grid grid-cols-2 gap-4 border-t border-border pt-4 text-sm">
                        <div>
                            <dt class="text-xs text-muted-foreground">Tanggal Pinjam</dt>
                            <dd class="mt-1">{{ $pinjaman->tanggal_pinjam?->format('d M Y') ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-muted-foreground">Jatuh Tempo</dt>
                            <dd class="mt-1 {{ $terlambat ? 'font-semibold text-destructive' : '' }}">{{ $pinjaman->tanggal_jatuh_tempo?->format('d M Y') ?? '—' }}</dd>
                        </div>
                    </dl>
                </article>
            @empty
                <div class="col-span-full rounded-2xl border border-dashed border-border bg-glass p-10 text-center">
                    <span class="text-4xl">👻</span>
                    <h2 class="mt-3 font-semibold">Belum ada pinjaman aktif</h2>
                    <p class="mt-1 text-sm text-muted-foreground">Unit yang Anda pinjam akan muncul di sini.</p>
                    <a href="{{ route('katalog') }}" class="mt-5 inline-flex rounded-lg bg-primary px-5 py-2.5 text-sm font-semibold text-primary-foreground transition hover:brightness-110">Jelajahi katalog</a>
                </div>
            @endforelse
        </div>

        <a href="{{ route('katalog') }}" class="mt-8 inline-flex text-sm font-medium text-primary transition hover:underline">← Kembali ke katalog</a>
    </main>
</div>
@endsection
