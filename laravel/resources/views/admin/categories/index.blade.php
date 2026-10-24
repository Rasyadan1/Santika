@extends('layouts.app')

@section('content')
<div class="flex min-h-screen flex-col bg-mystic md:flex-row">
    @include('partials.admin-navigation')

    <main class="min-w-0 flex-1 p-4 pb-12 md:p-8">
        @if (session('success'))<div class="mb-4 rounded-lg border border-success/50 bg-success/10 p-3 text-sm text-success">{{ session('success') }}</div>@endif
        @if (session('error'))<div class="mb-4 rounded-lg border border-destructive/50 bg-destructive/10 p-3 text-sm text-destructive">{{ session('error') }}</div>@endif
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div><p class="text-sm text-primary">Pengaturan Inventaris</p><h1 class="text-2xl font-bold">Kategori</h1><p class="text-sm text-muted-foreground">Atur pengelompokan unit mistis Santika.</p></div>
            <a href="{{ route('admin.categories.create') }}" class="rounded-lg bg-primary px-4 py-2.5 text-sm font-semibold text-primary-foreground transition hover:brightness-110">＋ Tambah Kategori</a>
        </div>

        <form action="{{ route('admin.categories.index') }}" method="GET" class="mt-6 flex w-full max-w-xl gap-2">
            <label for="category-search" class="sr-only">Cari kategori</label>
            <input id="category-search" type="search" name="search" value="{{ $search }}" placeholder="Cari nama atau deskripsi kategori..." class="min-w-0 flex-1 rounded-lg border border-input bg-background/60 px-4 py-2.5 text-sm outline-none placeholder:text-muted-foreground focus:border-primary focus:ring-2 focus:ring-primary/40">
            <button type="submit" class="rounded-lg bg-primary px-4 py-2.5 text-sm font-semibold text-primary-foreground transition hover:brightness-110">Cari</button>
            @if ($search)
                <a href="{{ route('admin.categories.index') }}" class="rounded-lg border border-border px-4 py-2.5 text-sm text-muted-foreground transition hover:bg-muted">Reset</a>
            @endif
        </form>

        <div class="mt-5 overflow-x-auto rounded-xl border border-border bg-card">
            <table class="w-full min-w-[700px] text-left text-sm">
                <thead class="border-b border-border bg-muted/50 text-xs uppercase tracking-wide text-muted-foreground"><tr><th class="px-5 py-3">Kategori</th><th class="px-5 py-3">Deskripsi</th><th class="px-5 py-3">Jumlah Unit</th><th class="px-5 py-3 text-right">Aksi</th></tr></thead>
                <tbody id="category-rows">
                    @forelse ($categories as $category)
                        <tr class="border-b border-border last:border-0 transition hover:bg-muted/40">
                            <td class="px-5 py-4 font-medium">{{ $category->nama_kategori }}</td>
                            <td class="px-5 py-4 text-muted-foreground">{{ $category->deskripsi ?: '—' }}</td>
                            <td class="px-5 py-4"><span class="rounded-full bg-accent px-2.5 py-1 text-xs font-semibold text-accent-foreground">{{ $category->units_count }} unit</span></td>
                            <td class="px-5 py-4"><div class="flex justify-end gap-2"><a href="{{ route('admin.categories.edit', $category) }}" class="rounded-lg border border-primary/50 px-3 py-1.5 text-xs font-medium text-primary transition hover:bg-primary/10">Edit</a><form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Hapus kategori {{ addslashes($category->nama_kategori) }}? Kategori yang masih dipakai unit tidak dapat dihapus.');">@csrf @method('DELETE')<button type="submit" class="rounded-lg border border-destructive/50 px-3 py-1.5 text-xs font-medium text-destructive transition hover:bg-destructive/10">Hapus</button></form></div></td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-5 py-10 text-center text-muted-foreground">{{ $search ? 'Tidak ada kategori yang cocok dengan pencarian.' : 'Belum ada kategori. Tambahkan kategori pertama untuk mulai mengelompokkan unit.' }}</td></tr>
                    @endforelse

                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $categories->links() }}</div>
    </main>
</div>
@endsection
