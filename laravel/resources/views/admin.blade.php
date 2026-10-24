@extends('layouts.app')

@section('content')
<div class="flex min-h-screen flex-col bg-mystic md:flex-row">
    @include('partials.admin-navigation')

    <main class="flex-1 p-4 pb-12 md:p-8">
        @if (session('success'))
            <div class="mb-4 rounded-lg border border-success/50 bg-success/10 p-3 text-sm text-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="mb-4 rounded-lg border border-destructive/50 bg-destructive/10 p-3 text-sm text-destructive">{{ session('error') }}</div>
        @endif

        <div class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold">Ringkasan Santika</h1>
                <p class="text-sm text-muted-foreground">Pantau data unit dan anggota dalam sistem.</p>
            </div>
            <div class="flex w-full flex-wrap gap-2 sm:w-auto">
                <a href="{{ route('admin.units.create') }}" class="rounded-lg bg-primary px-4 py-2.5 text-sm font-semibold text-primary-foreground transition hover:brightness-110">+ Tambah Unit</a>
                <form action="{{ route('admin.dashboard') }}" method="GET" class="flex min-w-0 flex-1 gap-2 sm:w-72 sm:flex-none">
                <input type="search" name="search" value="{{ $search }}" placeholder="Cari nama atau kode unit..." class="min-w-0 flex-1 rounded-lg border border-input bg-background/60 px-4 py-2.5 text-sm outline-none placeholder:text-muted-foreground focus:border-primary focus:ring-2 focus:ring-primary/40" />
                    <button type="submit" class="rounded-lg border border-border bg-card px-4 py-2.5 text-sm font-semibold transition hover:border-primary/60 hover:text-primary">Cari</button>
                </form>
            </div>
        </div>

        <div class="mt-6 grid gap-4 sm:grid-cols-3">
            <div class="rounded-xl border border-border bg-card p-5">
                <p class="text-sm text-muted-foreground">Total Unit</p>
                <p class="mt-1 text-3xl font-bold">{{ $totalUnit }}</p>
            </div>
            <div class="rounded-xl border border-border bg-card p-5">
                <p class="text-sm text-muted-foreground">Sedang Dipinjam</p>
                <p class="mt-1 text-3xl font-bold text-destructive">{{ $sedangDipinjam }}</p>
            </div>
            <div class="rounded-xl border border-border bg-card p-5">
                <p class="text-sm text-muted-foreground">Anggota Terdaftar</p>
                <p class="mt-1 text-3xl font-bold">{{ $totalAnggota }}</p>
            </div>
        </div>

        <div class="mt-6 overflow-x-auto rounded-xl border border-border bg-card">
            <table class="w-full min-w-[560px] text-left text-sm">
                <thead class="border-b border-border bg-muted/50 text-xs uppercase tracking-wide text-muted-foreground">
                    <tr>
                        <th class="px-5 py-3">Kode Unit</th>
                        <th class="px-5 py-3">Nama Unit</th>
                        <th class="px-5 py-3">Kategori</th>
                        <th class="px-5 py-3">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($units as $unit)
                        <tr class="border-b border-border last:border-0 transition hover:bg-muted/40">
                            <td class="px-5 py-3 font-mono text-xs text-muted-foreground">{{ $unit->kode_unit }}</td>
                            <td class="px-5 py-3 font-medium">{{ $unit->nama_unit }}</td>
                            <td class="px-5 py-3">
                                <div class="flex flex-wrap gap-1">
                                    @forelse ($unit->categories as $category)
                                        <span class="rounded-md bg-accent px-2 py-0.5 text-xs text-accent-foreground">{{ $category->nama_kategori }}</span>
                                    @empty
                                        <span class="text-xs text-muted-foreground">Belum dikategorikan</span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="px-5 py-3">
                                @if ($unit->status === 'tersedia')
                                    <span class="rounded-full bg-success/15 px-2.5 py-1 text-xs font-semibold text-success">Tersedia</span>
                                @else
                                    <span class="rounded-full bg-destructive/15 px-2.5 py-1 text-xs font-semibold text-destructive">Dipinjam</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-5 py-8 text-center text-muted-foreground">
                                @if ($search)
                                    Tidak ada unit yang cocok dengan pencarian “{{ $search }}”.
                                @else
                                    👻 Belum ada unit yang didaftarkan.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $units->links() }}</div>
    </main>
</div>
@endsection
