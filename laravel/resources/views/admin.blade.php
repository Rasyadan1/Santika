@extends('layouts.app')

@section('content')
<div class="flex min-h-screen flex-col bg-mystic md:flex-row">
    
    <!-- Sidebar Admin -->
    <aside class="border-b border-sidebar-border bg-sidebar md:sticky md:top-0 md:h-screen md:w-64 md:border-r md:border-b-0">
        <div class="px-6 py-5">
            <span class="font-display text-3xl tracking-wider text-primary">SANTIKA</span>
            <p class="text-xs text-muted-foreground">Panel Juru Kunci (Admin)</p>
        </div>
        <nav class="flex gap-1 overflow-x-auto px-3 pb-3 md:flex-col md:pb-0">
            <a href="/admin" class="flex shrink-0 items-center gap-3 rounded-lg px-3 py-2.5 text-sm transition bg-sidebar-accent text-sidebar-accent-foreground border-l-2 border-primary">
                <span>🗡️</span> Data Unit
            </a>
            <a href="#" class="flex shrink-0 items-center gap-3 rounded-lg px-3 py-2.5 text-sm transition text-muted-foreground hover:bg-sidebar-accent/50 hover:text-foreground">
                <span>🏷️</span> Data Kategori
            </a>
            <a href="#" class="flex shrink-0 items-center gap-3 rounded-lg px-3 py-2.5 text-sm transition text-muted-foreground hover:bg-sidebar-accent/50 hover:text-foreground">
                <span>🧙</span> Data Anggota
            </a>
            <a href="#" class="flex shrink-0 items-center gap-3 rounded-lg px-3 py-2.5 text-sm transition text-muted-foreground hover:bg-sidebar-accent/50 hover:text-foreground">
                <span>📜</span> Riwayat Peminjaman
            </a>
            <hr class="my-3 border-sidebar-border">
            <a href="/" class="flex shrink-0 items-center gap-3 rounded-lg px-3 py-2.5 text-sm transition text-destructive hover:bg-destructive/20">
                <span>🚪</span> Keluar
            </a>
        </nav>
    </aside>

    <!-- Main Content Area -->
    <main class="flex-1 p-4 pb-24 md:p-8">
        
        <!-- Flash Messages Placeholder -->
        @if(session('success'))
            <div class="mb-4 rounded border border-success/50 bg-success/10 p-3 text-sm text-success">
                {{ session('success') }}
            </div>
        @endif
        
        <!-- Header & Action Button -->
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold">Data Unit</h1>
                <p class="text-sm text-muted-foreground">Semua alat mistis yang siap disewakan.</p>
            </div>
            <!-- Tombol Tambah Unit (Siap diarahkan ke route form create) -->
            <a href="/admin/create" class="rounded-lg bg-primary px-5 py-2.5 text-sm font-semibold text-primary-foreground glow-primary hover:brightness-110 transition">
                + Tambah Unit Baru
            </a>
        </div>

        <!-- Summary Stat Cards -->
        <div class="mt-6 grid gap-4 sm:grid-cols-3">
            <div class="rounded-xl border border-border bg-card p-5">
                <p class="text-sm text-muted-foreground">Total Unit</p>
                <p class="mt-1 text-3xl font-bold">{{ $totalUnit ?? 0 }}</p>
            </div>
            <div class="rounded-xl border border-border bg-card p-5">
                <p class="text-sm text-muted-foreground">Sedang Dipinjam</p>
                <p class="mt-1 text-3xl font-bold text-destructive">{{ $sedangDipinjam ?? 0 }}</p>
            </div>
            <div class="rounded-xl border border-border bg-card p-5">
                <p class="text-sm text-muted-foreground">Anggota Terdaftar</p>
                <p class="mt-1 text-3xl font-bold">66</p> <!-- Dummy statis -->
            </div>
        </div>

        <!-- Data Table -->
        <div class="mt-6 overflow-x-auto rounded-xl border border-border bg-card">
            <table class="w-full min-w-[640px] text-left text-sm">
                <thead class="border-b border-border bg-muted/50 text-xs uppercase tracking-wide text-muted-foreground">
                    <tr>
                        <th class="px-5 py-3">Kode Unit</th>
                        <th class="px-5 py-3">Nama Unit</th>
                        <th class="px-5 py-3">Kategori</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($units as $unit)
                        <tr class="border-b border-border last:border-0 hover:bg-muted/40 transition">
                            <td class="px-5 py-3 font-mono text-xs text-muted-foreground">{{ $unit->kode_unit }}</td>
                            <td class="px-5 py-3 font-medium">
                                <span class="mr-2">{{ $unit->emoji ?? '🔮' }}</span>{{ $unit->nama_unit }}
                            </td>
                            <td class="px-5 py-3">
                                <div class="flex flex-wrap gap-1">
                                    @if(isset($unit->kategori) && is_array($unit->kategori))
                                        @foreach($unit->kategori as $cat)
                                            <span class="rounded-md bg-accent px-2 py-0.5 text-xs text-accent-foreground">{{ $cat }}</span>
                                        @endforeach
                                    @endif
                                </div>
                            </td>
                            <td class="px-5 py-3">
                                @if(($unit->status ?? 'Tersedia') == 'Tersedia')
                                    <span class="rounded-full px-2.5 py-1 text-xs font-semibold bg-success/15 text-success">Tersedia</span>
                                @else
                                    <span class="rounded-full px-2.5 py-1 text-xs font-semibold bg-destructive/15 text-destructive">Dipinjam</span>
                                @endif
                            </td>
                            <td class="px-5 py-3 text-right">
                                <!-- Tombol Edit (Siap diarahkan) -->
                                <a href="#" class="mr-2 rounded-md border border-border px-3 py-1.5 text-xs hover:border-primary hover:text-primary transition">Edit</a>
                                
                                <!-- Form Hapus (Menggunakan Form agar aman saat delete) -->
                                <form action="#" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin membakar (menghapus) unit ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="rounded-md border border-destructive/50 px-3 py-1.5 text-xs text-destructive hover:bg-destructive hover:text-destructive-foreground transition">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-8 text-center text-muted-foreground">
                                👻 Belum ada unit yang didaftarkan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </main>
</div>
@endsection