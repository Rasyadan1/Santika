@extends('layouts.app')

@section('content')
<div class="flex min-h-screen flex-col bg-mystic md:flex-row">
    @include('partials.admin-navigation')

    <main class="min-w-0 flex-1 p-4 pb-12 md:p-8">
        @if (session('success'))<div class="mb-4 rounded-lg border border-success/50 bg-success/10 p-3 text-sm text-success">{{ session('success') }}</div>@endif
        @if (session('error'))<div class="mb-4 rounded-lg border border-destructive/50 bg-destructive/10 p-3 text-sm text-destructive">{{ session('error') }}</div>@endif

        <div class="flex flex-wrap items-end justify-between gap-4">
            <div><p class="text-sm text-primary">Inventaris Santika</p><h1 class="text-2xl font-bold">Data Unit</h1><p class="text-sm text-muted-foreground">Kelola unit mistis, tarif, dan kategori.</p></div>
            <a href="{{ route('admin.units.create') }}" class="rounded-lg bg-primary px-4 py-2.5 text-sm font-semibold text-primary-foreground transition hover:brightness-110">＋ Tambah Unit</a>
        </div>

        <form action="{{ route('admin.units.index') }}" method="GET" class="mt-6 flex w-full max-w-xl gap-2">
            <label for="search" class="sr-only">Cari unit</label>
            <input id="search" type="search" name="search" value="{{ $search }}" placeholder="Cari nama atau kode unit..." class="min-w-0 flex-1 rounded-lg border border-input bg-background/60 px-4 py-2.5 text-sm outline-none placeholder:text-muted-foreground focus:border-primary focus:ring-2 focus:ring-primary/40" />
            <button type="submit" class="rounded-lg bg-primary px-4 py-2.5 text-sm font-semibold text-primary-foreground transition hover:brightness-110">Cari</button>
            @if ($search)<a href="{{ route('admin.units.index') }}" class="rounded-lg border border-border px-4 py-2.5 text-sm text-muted-foreground hover:bg-muted">Reset</a>@endif
        </form>

        <div class="mt-6 overflow-x-auto rounded-xl border border-border bg-card">
            <table class="w-full min-w-[900px] text-left text-sm">
                <thead class="border-b border-border bg-muted/50 text-xs uppercase tracking-wide text-muted-foreground"><tr><th class="px-5 py-3">Kode</th><th class="px-5 py-3">Nama Unit</th><th class="px-5 py-3">Kategori</th><th class="px-5 py-3">Tarif / Hari</th><th class="px-5 py-3">Status</th><th class="px-5 py-3 text-right">Aksi</th></tr></thead>
                <tbody>
                    @forelse ($units as $unit)
                        <tr class="border-b border-border last:border-0 transition hover:bg-muted/40">
                            <td class="px-5 py-4 font-mono text-xs text-muted-foreground">{{ $unit->kode_unit }}</td>
                            <td class="px-5 py-4"><p class="font-medium">{{ $unit->nama_unit }}</p><p class="mt-1 max-w-xs truncate text-xs text-muted-foreground">{{ $unit->deskripsi ?: 'Tanpa deskripsi' }}</p></td>
                            <td class="px-5 py-4"><div class="flex flex-wrap gap-1">@forelse ($unit->categories as $category)<span class="rounded-md bg-accent px-2 py-0.5 text-xs text-accent-foreground">{{ $category->nama_kategori }}</span>@empty<span class="text-xs text-muted-foreground">Belum dikategorikan</span>@endforelse</div></td>
                            <td class="px-5 py-4 whitespace-nowrap">Rp{{ number_format($unit->tarif_per_hari, 0, ',', '.') }}</td>
                            <td class="px-5 py-4">@if ($unit->status === 'tersedia')<span class="rounded-full bg-success/15 px-2.5 py-1 text-xs font-semibold text-success">Tersedia</span>@else<span class="rounded-full bg-destructive/15 px-2.5 py-1 text-xs font-semibold text-destructive">Dipinjam</span>@endif</td>
                            <td class="px-5 py-4"><div class="flex justify-end gap-2"><a href="{{ route('admin.units.edit', $unit) }}" class="rounded-lg border border-primary/50 px-3 py-1.5 text-xs font-medium text-primary transition hover:bg-primary/10">Edit</a><form action="{{ route('admin.units.destroy', $unit) }}" method="POST" onsubmit="return confirm('Hapus unit {{ addslashes($unit->nama_unit) }}? Unit dengan pinjaman aktif atau riwayat pinjaman tidak dapat dihapus.');">@csrf @method('DELETE')<button type="submit" class="rounded-lg border border-destructive/50 px-3 py-1.5 text-xs font-medium text-destructive transition hover:bg-destructive/10">Hapus</button></form></div></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-5 py-10 text-center text-muted-foreground">{{ $search ? 'Tidak ada unit yang cocok dengan pencarian.' : '👻 Belum ada unit yang didaftarkan.' }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $units->links() }}</div>
    </main>
</div>
@endsection
